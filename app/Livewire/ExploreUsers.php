<?php

namespace App\Livewire;

use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ExploreUsers extends Component
{
    use WithPagination;

    public string $query = '';
    public string $role = '';
    public string $sort = 'newest';

    /**
     * Sync filter state with URL for shareable links.
     * ?q=john&role=seeker&sort=followers
     */
    protected $queryString = [
        'query' => ['except' => '', 'as' => 'q'],
        'role' => ['except' => '', 'as' => 'role'],
        'sort' => ['except' => 'newest', 'as' => 'sort'],
    ];

    public function mount(): void
    {
        $this->hydrateFiltersFromUrl();
    }

    protected function hydrateFiltersFromUrl(): void
    {
        $allowedRoles = ['seeker', 'employer', 'company', 'admin', ''];
        if (!in_array($this->role, $allowedRoles, true)) {
            $this->role = '';
        }

        $allowedSorts = ['newest', 'name', 'username', 'followers'];
        if (!in_array($this->sort, $allowedSorts, true)) {
            $this->sort = 'newest';
        }
    }

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->query = '';
        $this->role = '';
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function render(UserRepository $userRepository): View
    {
        $filters = [
            'query' => trim($this->query),
            'role' => $this->role ?: null,
            'sort' => $this->sort,
        ];

        $users = $userRepository->listUsers(12, auth()->id(), array_filter($filters));

        return view('livewire.explore-users', [
            'users' => $users,
            'roleOptions' => [
                '' => 'All roles',
                'seeker' => 'Seeker',
                'employer' => 'Employer',
                'company' => 'Company',
                'admin' => 'Admin',
            ],
            'sortOptions' => [
                'newest' => 'Newest first',
                'name' => 'Name (A-Z)',
                'username' => 'Username (A-Z)',
                'followers' => 'Most followers',
            ],
        ]);
    }
}
