<?php


use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }


    public function test_retrieve_employee(): void
    {
        $this->actingAs($this->user)
            ->get('/api/employee')
            ->assertOk();
    }

    public function test_store_employee()
    {
        $tempFilePath = tempnam(sys_get_temp_dir(), 'test_image');
        file_put_contents($tempFilePath, 'Test file content');
        $file = new UploadedFile($tempFilePath, 'test_image.txt', 'text/plain', null, true);


         $response =$this->actingAs($this->user)
            ->post('/api/employee', [
                'name' => 'name',
                'lastname' => 'lastname',
                'surname' => 'surname',
                'image' => $file,
                'position_id' => Position::factory()->create(),
            ]);

         $response->assertCreated();

         $this->assertDatabaseHas('employees', [
             'name' => 'name'
         ]);


        Storage::disk('public')->assertExists('employeePhoto/' . $file->hashName());

        unlink($tempFilePath);
    }


    public function test_update_employee()
    {
        $employee = Employee::factory()->create();
        $response =$this->actingAs($this->user)
            ->patch('/api/employee/' . $employee->id, [
                'name' => 'name',
                'lastname' => 'lastname',
                'surname' => 'surname',
                'position_id' => Position::factory()->create()->id,
            ]);
        $response->assertOk();
    }








}
