<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
use App\Traits\ImageUtilsTrait;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes, CustomAttributes, CustomActions, JalaliDate;
	use ImageUtilsTrait;

    public $table = 'users';

	protected $guarded = ['avatar_id'];


	public $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'mobile_visibility',
        'email',
        'password',
        'token',
        'role_id',
        'invitation_code',
        'invited_by',
        'mobile_verified_at',
        'email_verified_at',
        'remember_token'
    ];

    public static $createRules = [
        'mobile' => 'required|digits_between:9,25|unique:users,mobile',
        'first_name' => 'nullable|max:50|min:3',
        'last_name' => 'nullable|max:50|min:3',
        /* profile fields */
        'female' => 'nullable|boolean',
        'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'role' => 'required|integer|exists:roles,id',
        'password' => ['required', 'confirmed', 'min:8', 'max:50', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        'password_confirmation' => ['required', 'same:password', 'max:50', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    ];

    public static $updateRules = [
        'first_name' => 'nullable|max:250|min:3',
        'last_name' => 'nullable|max:250|min:3',
        /* profile fields */
        'female' => 'nullable|boolean',
        'about' => 'nullable|string|max:1500|min:6',
        'specialist' => 'nullable|max:1500|min:6',
        'company' => 'nullable|max:250|min:6',
        'address' => 'nullable|max:250|min:6',
        'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'age' => 'nullable|digits:2',
        'password' => ['nullable', 'confirmed', 'min:8', 'max:50', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        'password_confirmation' => ['nullable', 'same:password', 'max:50', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    ];

    //protected $dates=['deleted_at'];

    public function activation()
    {
        return $this->hasOne(Activation::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name ? "{$this->first_name} {$this->last_name}" : "{$this->username}";
    }

    public function getUrlNameAttribute()
    {
        return $this->first_name ? "{$this->first_name}-{$this->last_name}" : "{$this->username}";
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

	public function user_login()
	{
		return $this->hasOne(UserLogin::class, 'user_id');
	}

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'admin_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ads()
    {
        return $this->hasMany(Ads::class, 'user_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Ads::class, 'bookmarks');
    }

//    ************************************************** api section ***********************

    /**
     * @param int $userId
     * @return bool|int
     */
    public function removeUserToken(int $userId)
    {
        $user = $this
            ->findOrFail($userId);
        return $user->update([
            'token' => false,
            'mobile_verified_at' => null
        ]);
    }

    //    ************************************************** auth section ***********************
    public function supports()
    {
        return $this->hasMany(Support::class, 'user_id');
    }

    public function adminSupports()
    {
        return $this->hasMany(Support::class, 'admin_id');
    }

    public function permissions()
    {
        return $this->role->permissions;
    }

    public function isSuperAdmin()
    {
        if (empty($this->permissions()->whereIn('name', '*')->first())) {
            return false;
        }

        return true;
    }

    public function hasPermission($permission)
    {
        if (is_array($permission)) {
            if (empty($permission = $this->permissions()->whereIn('name', $permission)->first())) {
                return false;
            }

            return $permission;
        }

        if (empty($permission = $this->permissions()->where('name', $permission)->first())) {
            return false;
        }

        return $permission;
    }

    public static function getAllUsers()
    {
        return self::query()
            ->where('id', '<>', auth()->user()->id)
            ->whereHas('role', function ($query) {
                $query->where('name', '<>', 'superAdmin');
            });
    }

    public static function getStandardUsers()
    {
        return self::query()->whereHas('role', function ($query) {
            $query->where('name', 'standardUser');
        });
    }

    public static function getAdminUsers()
    {
        return self::query()->whereHas('role', function ($query) {
            $query->where('name', '<>', 'standardUser')->where('name', '<>', 'superAdmin');
        });
    }

    public function profileImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'userAvatar');
    }

    public function getAvatarAttribute()
    {
        $image = $this->profileImages()
            ->first();

        if (!empty($image)) {
            $path = Storage::disk(config('image.storage.global'))->url('') . 'userAvatar/';
            $existImages['id'] = $image->id;

            foreach (config('image.sizes.image') as $imageSize => $value) {
                $existImages[$imageSize] = $path . $this->makeImageName($image->title, $image->ext, $value['postfix']);
            }

            return $existImages;
        }

	    $path = asset('assets/index/img/content');
	    $noImages['id']=null;
	    foreach (config('image.sizes.image') as $imageSize => $value) {
		    $noImages[$imageSize] = $path .'/ic_no_avatar'. $value['postfix'].'.png';
	    }

	    return $noImages;
    }

    public function ActiveAndPublishedAds()
    {
        return $this->ads()
            ->whereNotNull('published_at')
            ->where('start_date', '<', Carbon::now())
            ->where('end_date', '>', Carbon::now())
            ->orderBy('created_at', 'desc');
    }

    public function ActiveAds()
    {
       return $this->ads()
            ->where('start_date', '<', Carbon::now())
            ->where('end_date', '>', Carbon::now())
           ->orderBy('created_at', 'desc');
    }


	public function getUserLogin()
	{
		return $this->user_login()->first();
	}


}
