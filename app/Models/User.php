<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'force_logout'
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
    ];

    public static function saveUser($request, $user = false, $redirect, $role_id)
    {

        $bNewUser = false;

        if (!$user) {
            $user = new User();
            $bNewUser = true;
        }

        $oSelect = $user::where("email", $request->email);

        if (!$bNewUser) {
            $oSelect->where("id", "!=", $user->id);
        } 

        if(count($oSelect->get())) {
            return redirect()->back()->withError("Такой E-mail уже зарегистрирован!");
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password_first) && $request->password_first == $request->password_second) {
            $user->password = Hash::make($request->password_first);
            if (!$bNewUser) {
                $user->force_logout = 1;
            }
        }

        $user->active = $request->active == 'on' ? 1 : 0;

        $user->save();

        if ($bNewUser) {
            DB::table('model_has_roles')->insert([
                'role_id' => $role_id,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id
            ]);
        }

        $message = "Пользователь был успешно сохранен!";

        if (Arr::get($_REQUEST, 'apply') > 0) {
            return redirect()->to($redirect)->withSuccess($message);
        } else {
            return redirect()->back()->withSuccess($message);
        }
    }

    public function getUsersByRoles(array $roles) 
    {
        return User::select('users.id', 'users.name as user_name', 'users.email', 'active')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.name', $roles)
            ->get();
    }

    public static function deleteUser(User $user) 
    {
        DB::table('model_has_roles')->where('model_id', '=', $user->id)->delete();

        $user->delete();

        return redirect()->back()->withSuccess('Пользователь был успешно удален!');
    }
}
