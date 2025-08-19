<?php
// app/Models/User.php - UPDATED

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'last_login_at',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin' && $this->is_active;
    }

    /**
     * Check if user is super admin (first admin created)
     */
    public function isSuperAdmin()
    {
        return $this->isAdmin() && $this->id === 1;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 'user' && $this->is_active;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get role badge class
     */
    public function getRoleBadgeAttribute()
    {
        return $this->role === 'admin' ? 'bg-primary' : 'bg-secondary';
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return '-';

        // Format Indonesian phone number
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        }
        return $phone;
    }

    /**
     * Get last login formatted
     */
    public function getLastLoginFormattedAttribute()
    {
        if (!$this->last_login_at) return 'Belum pernah login';

        return $this->last_login_at->format('d/m/Y H:i');
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Relationship with rental histories
     */
    public function rentalHistories()
    {
        return $this->hasMany(RentalHistory::class, 'created_by');
    }

    /**
     * Relationship with creator (who created this user)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with created users (users created by this user)
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }
}
