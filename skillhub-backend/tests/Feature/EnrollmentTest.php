<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Formation;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    // Crée un formateur + une formation pour les tests
    private function creerFormation(): array
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $token = auth('api')->login($formateur);
        $formation = Formation::factory()->create(['formateur_id' => $formateur->id]);
        return ['formation' => $formation, 'token' => $token, 'formateur' => $formateur];
    }

    // Crée un apprenant connecté
    private function creerApprenant(): array
    {
        $apprenant = User::factory()->create(['role' => 'apprenant']);
        $token = auth('api')->login($apprenant);
        return ['apprenant' => $apprenant, 'token' => $token];
    }

    public function test_un_apprenant_peut_sinscrire_a_une_formation()
    {
        $data = $this->creerFormation();
        $apprenant = $this->creerApprenant();

        $response = $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        $response->assertStatus(201);
        $this->assertDatabaseHas('enrollments', [
            'utilisateur_id' => $apprenant['apprenant']->id,
            'formation_id'   => $data['formation']->id,
        ]);
    }

    public function test_un_apprenant_ne_peut_pas_sinscrire_deux_fois()
    {
        $data = $this->creerFormation();
        $apprenant = $this->creerApprenant();

        // Première inscription
        $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        // Deuxième inscription — doit échouer
        $response = $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        $response->assertStatus(409);
    }

    public function test_un_apprenant_peut_se_desinscrire()
    {
        $data = $this->creerFormation();
        $apprenant = $this->creerApprenant();

        // S'inscrire d'abord
        $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        // Se désinscrire
        $response = $this->deleteJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        $response->assertStatus(200);
        $this->assertDatabaseMissing('enrollments', [
            'utilisateur_id' => $apprenant['apprenant']->id,
            'formation_id'   => $data['formation']->id,
        ]);
    }

    public function test_un_apprenant_peut_voir_ses_formations()
    {
        $data = $this->creerFormation();
        $apprenant = $this->creerApprenant();

        // S'inscrire
        $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        // Voir ses formations
        $response = $this->getJson(
            '/api/apprenant/formations',
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        $response->assertStatus(200);
    }

    public function test_un_apprenant_peut_mettre_a_jour_sa_progression()
    {
        $data = $this->creerFormation();
        $apprenant = $this->creerApprenant();

        // S'inscrire
        $this->postJson(
            "/api/formations/{$data['formation']->id}/inscription",
            [],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        // Mettre à jour la progression
        $response = $this->putJson(
            "/api/formations/{$data['formation']->id}/progression",
            ['progression' => 75],
            ['Authorization' => "Bearer {$apprenant['token']}"]
        );

        $response->assertStatus(200);
    }
}