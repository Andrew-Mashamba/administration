<?php

namespace App\Livewire;

use App\Models\Institution;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;
use App\Services\SaccoProvisioner;
use App\Jobs\ProvisionSaccoJob;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class SaccosManagement extends Component
{
    use WithPagination;

    public $saccos;
    public $isViewing = false;
    public $isCreating = false;
    public $isEditing = false;
    public $editingId = null;
    // public $isLoading = false;
    public $totalInstitutions;
    public $activeInstitutions;
    public $inactiveInstitutions;
    public $pendingInstitutions;
    public $provisioningInstitutions;
    public $provisionedInstitutions;
    public $failedInstitutions;

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

    public $institution_type;
    public $status;

    // Manager Information
    public $manager_email;
    public $manager_phone_number;

    // IT Information
    public $it_email;
    public $it_phone_number;

    public $submenu;

    public $search = '';

    public function mount()
    {
        $this->submenu = 'management'; //default submenu
        $this->totalInstitutions = Institution::count();
        $this->activeInstitutions = Institution::where('status', 'active')->count();
        $this->inactiveInstitutions = Institution::where('status', 'inactive')->count();
        $this->microfinanceInstitutions = Institution::where('institution_type', 'microfinance')->count();
        $this->pendingInstitutions = Institution::where('status', 'pending')->count();
        $this->saccoInstitutions = Institution::where('institution_type', 'saccos')->count();
        $this->activeMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'active')->count();
        $this->activeSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'active')->count();
        $this->inactiveMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'inactive')->count();
        $this->inactiveSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'inactive')->count();
       
    }
    
    // public function institutionsCount()
    // {
    //         $totalInstitutions = Institution::count();
    //         $activeInstitutions = Institution::where('status', 'active')->count();
    //         $inactiveInstitutions = Institution::where('status', 'inactive')->count();
    //         $microfinanceInstitutions = Institution::where('institution_type', 'microfinance')->count();
    //         $saccoInstitutions = Institution::where('institution_type', 'saccos')->count();
    //         $activeMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'active')->count();
    //         $activeSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'active')->count();
    //         $inactiveMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'inactive')->count();
    //         $inactiveSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'inactive')->count();
          
        
    // }


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

        'institution_type' => 'required|string',
        // 'status' => 'required|string',

            'alias' => ['required', 'string', 'unique:institutions,alias' . ($this->editingId ? ",{$this->editingId}" : '')],
            'db_name' => ['required', 'string', 'unique:institutions,db_name' . ($this->editingId ? ",{$this->editingId}" : '')],
            'db_host' => 'required|string',
            'institution_id' => ['required', 'string', 'unique:institutions,institution_id' . ($this->editingId ? ",{$this->editingId}" : '')],

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
            $this->status = 'active';
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
            'institution_type', 'status',
            'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
        ]);
    }

    public function saveSaccos()
    {
        $this->isLoading = true;

        try {
            DB::beginTransaction();

            // Validate input
            $this->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'alias' => 'required|string|max:50|unique:institutions,alias',
                'db_name' => 'required|string|max:50|unique:institutions,db_name',
                'db_host' => 'required|string|max:255',
                'db_user' => 'required|string|max:255',
                'db_password' => 'required|string|max:255',
                'institution_id' => 'required|string|max:50|unique:institutions,institution_id',
                'manager_email' => 'required|email|max:255',
                'manager_phone_number' => 'required|string|max:20',
                'it_email' => 'required|email|max:255',
                'it_phone_number' => 'required|string|max:20',
                'institution_type' => 'required|string|max:50',
                'status' => 'required|string|max:20',
            ]);

            // Test database connection before saving
            // try {
            //     $testConnection = new PDO(
            //         "pgsql:host={$this->db_host};dbname={$this->db_name}",
            //         $this->db_user,
            //         $this->db_password
            //     );
            // } catch (PDOException $e) {
            //     throw new \Exception('Invalid database credentials: ' . $e->getMessage());
            // }

            // Create institution with encrypted sensitive data
            $institution = Institution::create([
                'name' => $this->name,
                'location' => $this->location,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'email' => $this->email,
                'alias' => $this->alias,
                'db_name' => $this->db_name,
                'db_host' => $this->db_host,
                'db_user' => encrypt($this->db_user),
                'db_password' => encrypt($this->db_password),
                'institution_id' => $this->institution_id,
                'manager_email' => $this->manager_email,
                'manager_phone_number' => $this->manager_phone_number,
                'it_email' => $this->it_email,
                'it_phone_number' => $this->it_phone_number,
                'institution_type' => $this->institution_type,
                'status' => $this->status,
            ]);

            // Dispatch the provisioning job
            ProvisionSaccoJob::dispatch(
                $this->alias,
                $this->db_name,
                $this->db_host,
                $this->db_user,
                $this->db_password,
                $this->manager_email,
                $this->it_email
            );

            DB::commit();
            session()->flash('message', 'SACCO creation initiated. Provisioning is in progress.');

            // Reset form
            $this->reset([
                'name', 'location', 'contact_person', 'phone', 'email',
                'institution_type', 'status',
                'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
                'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number'
            ]);

            $this->isCreating = false;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function softDeleteSaccos($id)
    {
        $saccos = Institution::findOrFail($id);
        $saccos->delete();
        session()->flash('message', 'Institution soft deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $this->status = $this->status === 'active' ? 'inactive' : 'active';

        if ($id) {
            $saccos = Institution::findOrFail($id);
            $saccos->status = $this->status;
            $saccos->save();
            session()->flash('message', 'Institution status updated successfully.');
        }
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
        $this->institution_type = $saccos->institution_type;
        // $this->status = $saccos->status;
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
                'institution_type' => $this->institution_type,
                // 'status' => $this->status,
            ]);

            $this->isEditing = false;
            $this->isViewing = false;
            $this->isCreating = false;
            $this->editingId = null;

            $this->reset([
                'name', 'location', 'contact_person', 'phone', 'email',
                'alias', 'db_name', 'db_user', 'db_password', 'institution_id',
                'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number',
                'institution_type', // 'status',
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
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number',
            'institution_type', // 'status',
        ]);
    }

    public function cancelView()
    {
        $this->isViewing = false;
        $this->isEditing = false;
        $this->isCreating = false;
        $this->editingId = null;
    }

    public function cancelCreate()
    {
        $this->isCreating = false;
        $this->reset([
            'name', 'location', 'contact_person', 'phone', 'email',
            'alias', 'db_name', 'db_host', 'db_user', 'db_password', 'institution_id',
            'manager_email', 'manager_phone_number', 'it_email', 'it_phone_number',
            'institution_type', // 'status',
        ]);
    }

    public function switchSubmenu($submenu)
    {
        $this->submenu = $submenu;
    }

    public function render()
    {
        return view('livewire.saccos-management', [
            'saccosList' => Institution::when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->paginate(10),
        ]);
    }
}
