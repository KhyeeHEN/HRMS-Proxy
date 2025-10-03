<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kpi;

class KpiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @var User
     */
    protected $manager;

    /**
     * @var User
     */
    protected $staff;

    /**
     * Set up the test environment by fetching existing users.
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Assuming 'manager.one' and 'staff.one' are already in the database
        $this->manager = User::where('name', 'manager.one')->first();
        $this->staff = User::where('name', 'staff.one')->first();

        // Optional: If you need to ensure they exist for the test to run
        if (!$this->manager) {
            $this->manager = User::factory()->create(['name' => 'manager.one', 'access' => 'Manager']);
        }

        if (!$this->staff) {
            $this->staff = User::factory()->create(['name' => 'staff.one', 'access' => 'Employee']);
        }
    }

    /**
     * Test that a manager can successfully create a KPI for staff with valid data.
     *
     * @return void
     */
    public function test_manager_can_create_a_kpi_for_staff_with_valid_data(): void
    {
        // Act as the manager
        $this->actingAs($this->manager);

        // Define valid KPI data where the total weightage is 100%
        $kpiData = [
            'staff_id' => $this->staff->id,
            'manager_id' => $this->manager->id,
            'key_goal_1' => $this->faker->sentence(5),
            'indicator_measurement_1' => $this->faker->text(100),
            'weightage_1' => 40,
            'key_goal_2' => $this->faker->sentence(5),
            'indicator_measurement_2' => $this->faker->text(100),
            'weightage_2' => 30,
            'key_goal_3' => $this->faker->sentence(5),
            'indicator_measurement_3' => $this->faker->text(100),
            'weightage_3' => 20,
            'key_goal_4' => $this->faker->sentence(5),
            'indicator_measurement_4' => $this->faker->text(100),
            'weightage_4' => 10,
            'key_goal_5' => '',
            'indicator_measurement_5' => '',
            'weightage_5' => null,
            'action' => 'submit'
        ];
        
        // Calculate total_weightage and add it to the data array
        $kpiData['total_weightage'] = 
            $kpiData['weightage_1'] + 
            $kpiData['weightage_2'] + 
            $kpiData['weightage_3'] + 
            $kpiData['weightage_4'];
        
        // Assert initial state: no KPI with this specific data exists in the database
        $this->assertDatabaseMissing('kpis', ['staff_id' => $this->staff->id, 'manager_id' => $this->manager->id]);

        // Submit the form
        $response = $this->post(route('kpi.store'), $kpiData);

        // Assert that the request was successful and redirected
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'KPI has been successfully created and is now "for review".');

        // Assert that the KPI was correctly stored in the database
        $this->assertDatabaseHas('kpis', [
            'staff_id' => $this->staff->id,
            'manager_id' => $this->manager->id,
            'status' => 'for review',
            'weightage_1' => 40,
            'weightage_2' => 30,
            'weightage_3' => 20,
            'weightage_4' => 10,
            'weightage_5' => null,
            'total_weightage' => 100,
        ]);
    }

    /**
     * Test that KPI creation fails when total weightage is not 100%.
     *
     * @return void
     */
    public function test_kpi_creation_fails_with_invalid_total_weightage(): void
    {
        // Act as the manager
        $this->actingAs($this->manager);

        // Define invalid KPI data where the total weightage is not 100%
        $kpiData = [
            'staff_id' => $this->staff->id,
            'manager_id' => $this->manager->id,
            'key_goal_1' => $this->faker->sentence(5),
            'indicator_measurement_1' => $this->faker->text(100),
            'weightage_1' => 50,
            'key_goal_2' => $this->faker->sentence(5),
            'indicator_measurement_2' => $this->faker->text(100),
            'weightage_2' => 30,
            'key_goal_3' => $this->faker->sentence(5),
            'indicator_measurement_3' => $this->faker->text(100),
            'weightage_3' => 20,
            'key_goal_4' => $this->faker->sentence(5),
            'indicator_measurement_4' => $this->faker->text(100),
            'weightage_4' => 10,
            'key_goal_5' => '',
            'indicator_measurement_5' => '',
            'weightage_5' => null,
            'action' => 'submit'
        ];
        
        // Calculate total_weightage and add it to the data array
        $kpiData['total_weightage'] = 
            $kpiData['weightage_1'] + 
            $kpiData['weightage_2'] + 
            $kpiData['weightage_3'] + 
            $kpiData['weightage_4'];

        // Submit the form
        $response = $this->post(route('kpi.store'), $kpiData);

        // Assert that the request was redirected back with a validation error
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['total_weightage']);

        // Assert that no new KPI record was created in the database
        $this->assertDatabaseMissing('kpis', ['staff_id' => $this->staff->id]);
    }
}