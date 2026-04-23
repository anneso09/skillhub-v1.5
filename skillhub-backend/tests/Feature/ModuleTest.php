<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Formation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    // Crée un formateur avec une formation et retourne le token
    private function setup_formateur(): array
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $token = auth('api')->login($formateur);
        $formation = Formation::factory()->create(['formateur_id' => $formateur->id]);
        return ['formateur' => $formateur, 'token' => $token, 'formation' => $formation];
    }

    public function test_on_peut_voir_les_modules_dune_formation()
    {
        $data = $this->setup_formateur();

        $response = $this->getJson("/api/formations/{$data['formation']->id}/modules");

        $response->assertStatus(200);
    }

    public function test_un_formateur_peut_ajouter_un_module()
    {
        $data = $this->setup_formateur();

        $response = $this->postJson(
            "/api/formations/{$data['formation']->id}/modules",
            [
                'titre'   => 'Module de test',
                'contenu' => 'Contenu du module de test',
                'ordre'   => 1,
            ],
            ['Authorization' => "Bearer {$data['token']}"]
        );

        $response->assertStatus(201);
        $this->assertDatabaseHas('modules', [
            'titre'        => 'Module de test',
            'formation_id' => $data['formation']->id,
        ]);
    }

    public function test_un_formateur_peut_modifier_un_module()
    {
        $data = $this->setup_formateur();

        // Créer un module d'abord
        $moduleResponse = $this->postJson(
            "/api/formations/{$data['formation']->id}/modules",
            ['titre' => 'Module original', 'contenu' => 'Contenu', 'ordre' => 1],
            ['Authorization' => "Bearer {$data['token']}"]
        );

        $moduleId = $moduleResponse->json('module.id');

        // Modifier le module
        $response = $this->putJson(
            "/api/modules/{$moduleId}",
            ['titre' => 'Module modifié'],
            ['Authorization' => "Bearer {$data['token']}"]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('modules', ['titre' => 'Module modifié']);
    }

    public function test_un_formateur_peut_supprimer_un_module()
    {
        $data = $this->setup_formateur();

        // Créer un module
        $moduleResponse = $this->postJson(
            "/api/formations/{$data['formation']->id}/modules",
            ['titre' => 'Module à supprimer', 'contenu' => 'Contenu', 'ordre' => 1],
            ['Authorization' => "Bearer {$data['token']}"]
        );

        $moduleId = $moduleResponse->json('module.id');

        // Supprimer
        $response = $this->deleteJson(
            "/api/modules/{$moduleId}",
            [],
            ['Authorization' => "Bearer {$data['token']}"]
        );

        $response->assertStatus(200);
        $this->assertDatabaseMissing('modules', ['id' => $moduleId]);
    }

    public function test_un_apprenant_ne_peut_pas_ajouter_un_module()
    {
        $data = $this->setup_formateur();
        $apprenant = User::factory()->create(['role' => 'apprenant']);
        $tokenApprenant = auth('api')->login($apprenant);

        $response = $this->postJson(
            "/api/formations/{$data['formation']->id}/modules",
            ['titre' => 'Module interdit', 'contenu' => 'Contenu', 'ordre' => 1],
            ['Authorization' => "Bearer {$tokenApprenant}"]
        );

        $response->assertStatus(403);
    }
}