<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Formation;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_connecte_peut_voir_son_profil()
    {
        $user = User::factory()->create(['role' => 'apprenant']);
        $token = auth('api')->login($user);

        $response = $this->getJson(
            '/api/profile',
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'nom', 'prenom', 'email', 'role']);
    }

    public function test_un_utilisateur_peut_se_deconnecter()
    {
        $user = User::factory()->create(['role' => 'apprenant']);
        $token = auth('api')->login($user);

        $response = $this->postJson(
            '/api/logout',
            [],
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertStatus(200);
    }

    public function test_les_relations_du_model_user_fonctionnent()
    {
        // Couvre les relations formations() et enrollments() du model User
        $formateur = User::factory()->create(['role' => 'formateur']);
        $formation = Formation::factory()->create(['formateur_id' => $formateur->id]);

        $apprenant = User::factory()->create(['role' => 'apprenant']);
        Enrollment::create([
            'utilisateur_id' => $apprenant->id,
            'formation_id'   => $formation->id,
            'progression'    => 0,
        ]);

        // Teste la relation formations() du formateur
        $this->assertCount(1, $formateur->formations);

        // Teste la relation enrollments() de l'apprenant
        $this->assertCount(1, $apprenant->enrollments);

        // Teste la relation utilisateur() de l'enrollment
        $enrollment = $apprenant->enrollments->first();
        $this->assertEquals($apprenant->id, $enrollment->utilisateur->id);

        // Teste la relation formation() de l'enrollment
        $this->assertEquals($formation->id, $enrollment->formation->id);

        // Teste la relation formateur() de la formation
        $this->assertEquals($formateur->id, $formation->formateur->id);

        // Teste la relation modules() et enrollments() de la formation
        $this->assertNotNull($formation->modules);
        $this->assertNotNull($formation->enrollments);
    }

    public function test_les_formations_avec_filtres_sont_accessibles()
    {
        // Couvre les lignes de filtres dans FormationController
        $formateur = User::factory()->create(['role' => 'formateur']);
        Formation::factory()->create([
            'formateur_id' => $formateur->id,
            'categorie'    => 'Développement web',
            'niveau'       => 'Débutant',
            'titre'        => 'React pour débutants',
        ]);

        // Test filtre par catégorie
        $response = $this->getJson('/api/formations?categorie=Développement web');
        $response->assertStatus(200);

        // Test filtre par niveau
        $response = $this->getJson('/api/formations?niveau=Débutant');
        $response->assertStatus(200);

        // Test filtre par recherche
        $response = $this->getJson('/api/formations?search=React');
        $response->assertStatus(200);
    }

    public function test_la_consultation_dune_formation_incremente_les_vues()
    {
        // Couvre le show() du FormationController
        $formateur = User::factory()->create(['role' => 'formateur']);
        $formation = Formation::factory()->create([
            'formateur_id' => $formateur->id,
            'nombre_vues'  => 0,
        ]);

        $this->getJson("/api/formations/{$formation->id}");

        $formation->refresh();
        $this->assertEquals(1, $formation->nombre_vues);
    }

    public function test_le_dashboard_formateur_est_accessible()
    {
        // Couvre la route formateur/formations
        $formateur = User::factory()->create(['role' => 'formateur']);
        $token = auth('api')->login($formateur);
        Formation::factory()->create(['formateur_id' => $formateur->id]);

        $response = $this->getJson(
            '/api/formateur/formations',
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertStatus(200);
    }
}