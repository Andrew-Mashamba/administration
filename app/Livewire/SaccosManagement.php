<?php

namespace App\Livewire;

use App\Models\Institution;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;
use App\Services\SaccoProvisioner;

class SaccosManagement extends Component
{
    use WithPagination;

    public $saccos;
    public $isViewing = false;
    public $isCreating = false;
    public $isEditing = false;
    public $editingId = null;
    public $isLoading = false;

    // Basic Information
    public $name;
    public $location;
    public $contact_person;
    public $phone;
    public $email;

    // Database Information
    public $alias;
    public $db_name;
    public $db_host;
    public $db_user;
    public $db_password;
    public $institution_id;

    // Manager Information
    public $manager_email;
    public $manager_phone_number;

    // IT Information
    public $it_email;
    public $it_phone_number;

    // protected $rules = [
    //     'name' => 'required|min:3',
    //     'location' => 'nullable|string',
    //     'contact_person' => 'nullable|string',
    //     'phone' => 'nullable|string',
    //     'email' => 'nullable|email',

    //     'alias' => 'required|string|unique:institutions,alias',
    //     'db_name' => 'required|string|unique:institutions,db_name',
    //     'db_host' => 'required|string',
    //     'institution_id' => 'required|string|unique:institutions,institution_id',

    //     'manager_email' => 'nullable|email',
    //     'manager_phone_number' => 'nullable|string',
    //     'it_email' => 'nullable|email',
    //     'it_phone_number' => 'nullable|string',
    // ];

    
    public function rules()
    {
        return [
        'name' => 'required|min:3',
        'location' => 'nullable|string',
        'contact_person' => 'nullable|string',
        'phone' => 'nullable|string',
        'email' => 'nullable|email',

        'alias' => 'required|string|unique:institutions,alias,' . ($this->editingId),
        'db_name' => 'required|string|unique:institutions,db_name,' . ($this->editingId),
        // 'db_host' => 'required|string',
        'institution_id' => 'required|string|unique:institutions,institution_id,' . ($this->editingId),

        'manager_email' => 'nullable|email',
        'manager_phone_number' => 'nullable|string',
        'it_email' => 'nullable|email',
        'it_phone_number' => 'nullable|string',
        ];
    }

    public function updatedName($value)
    {
        if ($this->isCreating) {
            $this->alias = Str::slug($value);
            $this->db_name = 'db_' . str_replace('-', '_', $this->alias);
            $this->db_host = env('DB_HOST', '127.0.0.1');
            $this->db_user = env('DB_USERNAME');
            $this->db_password = env('DB_PASSWORD');
        }
    }

    public function viewSaccos($id)
    {
        $this->saccos = Institution::findOrFail($id);
        $this->isViewing = true;
        $this->isCreating = false;
    }

    public function createSaccos()
    {
        $this->isCreating = true;
        $this->isViewing = false;
        $this->isEditing = false;
        $this->reset([
            'name', 'location', 'contact_person', 'phone', 'email',
            'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
        ]);
    }

    public function saveSaccos()
    {
        $this->isLoading = true;
        // try {
            $this->validate();

            Institution::create([
                'name' => $this->name,
                'location' => $this->location,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'email' => $this->email,
                'alias' => $this->alias,
                'db_name' => $this->db_name,
                'db_host' => $this->db_host,
                'db_user' => $this->db_user,
                'db_password' => $this->db_password,
                'institution_id' => $this->institution_id,
                'manager_email' => $this->manager_email,
                'manager_phone_number' => $this->manager_phone_number,
                'it_email' => $this->it_email,
                'it_phone_number' => $this->it_phone_number,
            ]);

            $this->isCreating = false;

            try {
                (new SaccoProvisioner())->provisionWithDocker(
                    $this->alias,
                    $this->db_name,
                    $this->db_host,
                    $this->db_user,
                    $this->db_password
                );
                session()->flash('message', 'SACCO provisioned successfully via shared PostgreSQL!');
            } catch (\Exception $e) {
                session()->flash('error', 'Provisioning failed: ' . $e->getMessage());
            }

            $this->reset([
                'name', 'location', 'contact_person', 'phone', 'email',
                'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
                'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
            ]);

            session()->flash('message', 'Institution created successfully.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Error creating institution: ' . $e->getMessage());
        // } finally {
            $this->isLoading = false;
        // }
    }

    public function softDeleteSaccos($id)
    {
        $saccos = Institution::findOrFail($id);
        $saccos->delete();
        session()->flash('message', 'Institution soft deleted successfully.');
    }

    public function editSaccos($id)
    {
        $saccos = Institution::findOrFail($id);
        $this->editingId = $id;
        $this->isEditing = true;
        $this->isViewing = false;
        $this->isCreating = false;

        // Fill the form with existing data
        $this->name = $saccos->name;
        $this->location = $saccos->location;
        $this->contact_person = $saccos->contact_person;
        $this->phone = $saccos->phone;
        $this->email = $saccos->email;
        $this->alias = $saccos->alias;
        $this->db_name = $saccos->db_name;
        // $this->db_host = $saccos->db_host;
        $this->db_user = $saccos->db_user;
        $this->db_password = $saccos->db_password;
        $this->institution_id = $saccos->institution_id;
        $this->manager_email = $saccos->manager_email;
        $this->manager_phone_number = $saccos->manager_phone_number;
        $this->it_email = $saccos->it_email;
        $this->it_phone_number = $saccos->it_phone_number;
    }

    public function updateSaccos()
    {
        $this->isLoading = true;
        try {
            //find the id first to avoid errors for unique fields
            $saccos = Institution::findOrFail($this->editingId);
            $this->validate();

            $saccos->update([
                'name' => $this->name,
                'location' => $this->location,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'email' => $this->email,
                'alias' => $this->alias,
                'db_name' => $this->db_name,
                // 'db_host' => $this->db_host,
                'db_user' => $this->db_user,
                'db_password' => $this->db_password,
                'institution_id' => $this->institution_id,
                'manager_email' => $this->manager_email,
                'manager_phone_number' => $this->manager_phone_number,
                'it_email' => $this->it_email,
                'it_phone_number' => $this->it_phone_number,
            ]);

            $this->isEditing = false;
            $this->isViewing = false;
            $this->isCreating = false;
            $this->editingId = null;

            $this->reset([
                'name', 'location', 'contact_person', 'phone', 'email',
                'alias', 'db_name', 'db_user', 'db_password', 'institution_id',
                'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
            ]);

            session()->flash('message', 'Institution updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating institution: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->reset([
            'name', 'location', 'contact_person', 'phone', 'email',
            'alias', 'db_name', 'db_user', 'db_password', 'institution_id',
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
        ]);
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
        $this->reset([
            'name', 'location', 'contact_person', 'phone', 'email',
            'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
        ]);
    }

    public function render()
    {
        return view('livewire.saccos-management', [
            'saccosList' => Institution::paginate(10),
        ]);
    }
}
