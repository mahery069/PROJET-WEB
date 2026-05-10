<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestCase;

/**
 * @internal
 */
final class WalletIntegrationTest extends DatabaseTestCase
{
    protected $refresh = true;
    protected $seed = 'DatabaseSeeder';

    /**
     * Test: Get wallet balance for a user
     */
    public function testGetWalletBalance(): void
    {
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/balance?user_id=1');

        $response->assertResponseCode(200);
        $response->assertJSONExact([
            'success' => true,
            'message' => 'Solde récupéré',
            'data' => [
                'solde' => '0.00', // or seeded balance
            ],
            'errors' => null,
        ]);
    }

    /**
     * Test: Get wallet balance without user_id should fail
     */
    public function testGetWalletBalanceWithoutUserId(): void
    {
        $response = $this->get('/wallet/balance');

        $response->assertResponseCode(400);
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('missing', $result['errors']['user_id']);
    }

    /**
     * Test: Redeem a valid code
     */
    public function testRedeemValidCode(): void
    {
        // Create a code first
        $db = \Config\Database::connect();
        $db->table('codes_wallet')->insert([
            'code' => 'TEST1234',
            'montant' => 25.50,
            'est_utilise' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/redeem', ['code' => 'TEST1234']);

        $response->assertResponseCode(200);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertEquals('25.50', $result['data']['montant']);
    }

    /**
     * Test: Redeem invalid code should fail
     */
    public function testRedeemInvalidCode(): void
    {
        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/redeem', ['code' => 'INVALID']);

        $response->assertResponseCode(404);
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('not_found', $result['errors']['code']);
    }

    /**
     * Test: Redeem already used code should fail
     */
    public function testRedeemAlreadyUsedCode(): void
    {
        // Create and use a code
        $db = \Config\Database::connect();
        $db->table('codes_wallet')->insert([
            'code' => 'USED1234',
            'montant' => 10.00,
            'est_utilise' => 1,
            'id_utilisateur' => 2,
            'date_utilisation' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/redeem', ['code' => 'USED1234']);

        $response->assertResponseCode(409);
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('already_used', $result['errors']['code']);
    }

    /**
     * Test: Purchase regime with sufficient balance
     */
    public function testPurchaseRegimeWithSufficientBalance(): void
    {
        $db = \Config\Database::connect();
        
        // Create a regime
        $db->table('regimes')->insert([
            'nom' => 'Regime Test',
            'description' => 'Test regime',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => 50.00,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $regimeId = $db->insertID();

        // Create wallet with balance
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 100.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/purchase', ['regime_id' => $regimeId]);

        $response->assertResponseCode(201);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertEquals($regimeId, $result['data']['regime_id']);
        $this->assertEquals('50.00', $result['data']['solde_restant']);
    }

    /**
     * Test: Purchase regime with insufficient balance should fail
     */
    public function testPurchaseRegimeWithInsufficientBalance(): void
    {
        $db = \Config\Database::connect();
        
        // Create a regime
        $db->table('regimes')->insert([
            'nom' => 'Regime Expensive',
            'description' => 'Expensive test regime',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => 150.00,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $regimeId = $db->insertID();

        // Create wallet with low balance
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 50.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/purchase', ['regime_id' => $regimeId]);

        $response->assertResponseCode(402); // 402 Payment Required
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('insufficient_balance', $result['errors']['wallet']);
    }

    /**
     * Test: Purchase regime that doesn't exist should fail
     */
    public function testPurchaseNonExistentRegime(): void
    {
        // Create wallet
        $db = \Config\Database::connect();
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 100.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/purchase', ['regime_id' => 9999]);

        $response->assertResponseCode(404);
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('not_found', $result['errors']['regime_id']);
    }

    /**
     * Test: Get user subscriptions
     */
    public function testGetUserSubscriptions(): void
    {
        $db = \Config\Database::connect();
        
        // Create a regime
        $db->table('regimes')->insert([
            'nom' => 'Regime Sub Test',
            'description' => 'Test',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => 50.00,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $regimeId = $db->insertID();

        // Create subscription
        $db->table('user_regimes')->insert([
            'id_user' => 1,
            'id_regime' => $regimeId,
            'date_debut' => date('Y-m-d'),
            'date_fin' => date('Y-m-d', strtotime('+30 days')),
            'prix_paye' => 50.00,
            'est_actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/subscriptions?user_id=1');

        $response->assertResponseCode(200);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertCount(1, $result['data']);
        $this->assertEquals('Regime Sub Test', $result['data'][0]['nom']);
    }

    /**
     * Test: Purchase Gold with sufficient balance
     */
    public function testPurchaseGoldWithSufficientBalance(): void
    {
        $db = \Config\Database::connect();
        
        // Create wallet with balance
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 100.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/gold/purchase', ['user_id' => 1]);

        $response->assertResponseCode(201);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertTrue($result['data']['is_gold']);
        $this->assertEquals(15, $result['data']['remise_pourcentage']);
        $this->assertEquals('50.00', $result['data']['solde_restant']);
    }

    /**
     * Test: Purchase Gold with insufficient balance should fail
     */
    public function testPurchaseGoldWithInsufficientBalance(): void
    {
        $db = \Config\Database::connect();
        
        // Create wallet with low balance
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 20.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/gold/purchase', ['user_id' => 1]);

        $response->assertResponseCode(402); // 402 Payment Required
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('insufficient_balance', $result['errors']['wallet']);
    }

    /**
     * Test: Purchase Gold when already Gold should fail
     */
    public function testPurchaseGoldWhenAlreadyGold(): void
    {
        $db = \Config\Database::connect();
        
        // Update user to be Gold
        $db->table('users')->where('id', 1)->update(['is_gold' => 1]);
        
        // Create wallet
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 100.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/gold/purchase', ['user_id' => 1]);

        $response->assertResponseCode(409);
        $result = json_decode($response->getBody(), true);
        $this->assertFalse($result['success']);
        $this->assertEquals('already_gold', $result['errors']['gold']);
    }

    /**
     * Test: Get Gold status for non-Gold user
     */
    public function testGetGoldStatusForNonGoldUser(): void
    {
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/gold/status?user_id=1');

        $response->assertResponseCode(200);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertFalse($result['data']['is_gold']);
        $this->assertEquals(0, $result['data']['remise_pourcentage']);
    }

    /**
     * Test: Get Gold status for Gold user
     */
    public function testGetGoldStatusForGoldUser(): void
    {
        $db = \Config\Database::connect();
        $db->table('users')->where('id', 1)->update(['is_gold' => 1]);

        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/gold/status?user_id=1');

        $response->assertResponseCode(200);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertTrue($result['data']['is_gold']);
        $this->assertEquals(15, $result['data']['remise_pourcentage']);
    }

    /**
     * Test: Complete flow - redeem, purchase regime, then purchase Gold
     */
    public function testCompleteWalletFlow(): void
    {
        $db = \Config\Database::connect();
        
        // Step 1: Create a code and redeem it
        $db->table('codes_wallet')->insert([
            'code' => 'FLOW1234',
            'montant' => 150.00,
            'est_utilise' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/redeem', ['code' => 'FLOW1234']);
        $this->assertTrue(json_decode($response->getBody(), true)['success']);

        // Step 2: Verify balance
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/balance?user_id=1');
        $balance1 = json_decode($response->getBody(), true)['data']['solde'];
        $this->assertEquals('150.00', $balance1);

        // Step 3: Create and purchase regime
        $db->table('regimes')->insert([
            'nom' => 'Flow Test Regime',
            'description' => 'Test',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => 80.00,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $regimeId = $db->insertID();

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/purchase', ['regime_id' => $regimeId]);
        $this->assertTrue(json_decode($response->getBody(), true)['success']);

        // Step 4: Verify balance after purchase
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/balance?user_id=1');
        $balance2 = json_decode($response->getBody(), true)['data']['solde'];
        $this->assertEquals('70.00', $balance2);

        // Step 5: Purchase Gold
        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/gold/purchase', ['user_id' => 1]);
        $this->assertTrue(json_decode($response->getBody(), true)['success']);

        // Step 6: Verify final balance
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/balance?user_id=1');
        $balance3 = json_decode($response->getBody(), true)['data']['solde'];
        $this->assertEquals('20.00', $balance3);

        // Step 7: Verify Gold status
        $response = $this->withSession(['user_id' => 1])
            ->get('/wallet/gold/status?user_id=1');
        $goldStatus = json_decode($response->getBody(), true)['data']['is_gold'];
        $this->assertTrue($goldStatus);
    }

    /**
     * Test: Purchase regime with Gold discount applied
     */
    public function testPurchaseRegimeWithGoldDiscount(): void
    {
        $db = \Config\Database::connect();
        
        // Create a regime with known price
        $db->table('regimes')->insert([
            'nom' => 'Regime Gold Discount Test',
            'description' => 'Test regime for Gold discount',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => 100.00,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $regimeId = $db->insertID();

        // Set user as Gold
        $db->table('users')->where('id', 1)->update(['is_gold' => 1]);

        // Create wallet with sufficient balance
        $db->table('user_wallet')->insert([
            'id_user' => 1,
            'solde' => 100.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $response = $this->withSession(['user_id' => 1])
            ->post('/wallet/purchase', ['regime_id' => $regimeId]);

        $response->assertResponseCode(201);
        $result = json_decode($response->getBody(), true);
        $this->assertTrue($result['success']);
        $this->assertEquals(15, $result['data']['discount_applique']);
        $this->assertEquals('100.00', $result['data']['prix_original']);
        // 100.00 * 0.85 = 85.00
        $this->assertEquals('85.00', $result['data']['prix_paye']);
        // 100.00 - 85.00 = 15.00
        $this->assertEquals('15.00', $result['data']['solde_restant']);
        $this->assertStringContainsString('Remise Gold 15%', $result['message']);
    }

    /**
     * Test: Compare regime purchase price with and without Gold
     */
    public function testPurchaseRegimePriceComparison(): void
    {
        $db = \Config\Database::connect();
        
        // Create two regimes with same price
        $regimePrice = 80.00;
        
        // Regime 1 - for non-Gold user
        $db->table('regimes')->insert([
            'nom' => 'Regime Non-Gold',
            'description' => 'Test',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => $regimePrice,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $regimeId1 = $db->insertID();

        // Regime 2 - for Gold user
        $db->table('regimes')->insert([
            'nom' => 'Regime Gold',
            'description' => 'Test',
            'pourcentage_viande' => 30,
            'pourcentage_poisson' => 20,
            'pourcentage_volaille' => 20,
            'variation_poids' => -2.5,
            'duree_jours' => 30,
            'prix' => $regimePrice,
            'actif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $regimeId2 = $db->insertID();

        // User 1 - Non-Gold
        $db->table('user_wallet')->insert([
            'id_user' => 2,
            'solde' => 150.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // User 3 - Gold
        $db->table('users')->where('id', 3)->update(['is_gold' => 1]);
        $db->table('user_wallet')->insert([
            'id_user' => 3,
            'solde' => 150.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Purchase regime - Non-Gold user
        $response1 = $this->withSession(['user_id' => 2])
            ->post('/wallet/purchase', ['regime_id' => $regimeId1]);
        $result1 = json_decode($response1->getBody(), true);

        // Purchase regime - Gold user
        $response2 = $this->withSession(['user_id' => 3])
            ->post('/wallet/purchase', ['regime_id' => $regimeId2]);
        $result2 = json_decode($response2->getBody(), true);

        // Assertions
        $this->assertTrue($result1['success']);
        $this->assertTrue($result2['success']);

        // Non-Gold user pays full price
        $this->assertEquals('80.00', $result1['data']['prix_paye']);
        $this->assertEquals(0, $result1['data']['discount_applique']);

        // Gold user pays 15% less
        $this->assertEquals('68.00', $result2['data']['prix_paye']);  // 80.00 * 0.85 = 68.00
        $this->assertEquals(15, $result2['data']['discount_applique']);

        // Verify wallets updated correctly
        // Non-Gold: 150.00 - 80.00 = 70.00
        $this->assertEquals('70.00', $result1['data']['solde_restant']);
        // Gold: 150.00 - 68.00 = 82.00
        $this->assertEquals('82.00', $result2['data']['solde_restant']);
    }
}

