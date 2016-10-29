<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * An organization has only one owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organization()
    {
            return $this->hasOne(Organization::class, 'user_id', 'id');
    }

    /**
     * An organization can have multiple employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'user_organization', 'user_id', 'organization_id');
    }

    /**
     * Get all the wikis that are starred by a user.
     *
     * @return mixed
     */
    public function starWikis()
    {
        return $this->belongsToMany(Wiki::class, 'user_star', 'user_id', 'entity_id')->where('entity_type', '=', 'wiki');
    }

    /**
     * Get all the pages that are starred by a user.
     *
     * @return mixed
     */
    public function starPages()
    {
        return $this->belongsToMany(Wiki::class, 'user_star', 'user_id', 'entity_id')->where('entity_type', '=', 'page');
    }

    /**
     * Get all the wikis that are watched by a user.
     *
     * @return mixed
     */
    public function watchWikis()
    {
        return $this->belongsToMany(Wiki::class, 'user_watch', 'user_id', 'entity_id')->where('entity_type', '=', 'wiki');
    }

    /**
     * Get all the pages that are watched by a user.
     *
     * @return mixed
     */
    public function watchPages()
    {
        return $this->belongsToMany(Wiki::class, 'user_watch', 'user_id', 'entity_id')->where('entity_type', '=', 'page');
    }

    /**
     * A user can have many followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follow_id', 'user_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follow_id');
    }

    /**
     * A user can has many wikis.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wikis()
    {
        return $this->hasMany(Wiki::class, 'user_id', 'id')->latest();
    }

    /**
     * A user can create many pages in a wiki.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(WikiPage::class, 'user_id', 'id');
    }

    public function getUsers()
    {
        return $this->with(['followers', 'following'])->paginate(10);
    }

    public function getUser($userSlug)
    {
        $user = $this->where('slug', '=', $userSlug)->with(['organization', 'organizations', 'starWikis', 'starPages', 'watchWikis', 'watchPages', 'followers', 'following', 'wikis'])->first();
        
        if($user) {
            return $user;
        }
        return false;
    }

    public function getOrganizations($user)
    {
        $userOrganizations = $this->find($user->id)->organizations()->paginate(10);
        return $userOrganizations;
    }

    public function getFollowers($user)
    {
        $userFollowers = $this->find($user->id)->followers()->paginate(10);
        return $userFollowers;
    }

    public function getFollowing($user)
    {
        $userFollowing = $this->find($user->id)->following()->paginate(10);
        return $userFollowing;
    }

    public function getWikis($user)
    {        
        $userWikis = $this->find($user->id)->wikis()->paginate(10);
        return $userWikis;
    }

    public function followUser($followId)
    {
        return DB::table('user_followers')->insert([
            'user_id'    => Auth::user()->id,
            'follow_id'  => $followId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function unfollowUser($followId)
    {
        return DB::table('user_followers')->where([
            'user_id'    => Auth::user()->id,
            'follow_id'  => $followId,
        ])->delete();
    }
}
