<?php

namespace App\Policies;

use App\User;
use App\Sheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class SheetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the sheet.
     *
     * @param  \App\User  $user
     * @param  \App\Sheet  $sheet
     * @return mixed
     */
    public function view(User $user, Sheet $sheet)
    {
        if ($sheet->allow_view) {
            return true;
        }

        return $sheet->user_id === $user->id;
    }

    /**
     * Determine whether the user can create sheets.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(?User $user)
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the sheet.
     *
     * @param  User  $user
     * @param  Sheet  $sheet
     * @return mixed
     */
    public function update(User $user, Sheet $sheet)
    {
        return $user->id === $sheet->user_id;
    }

    /**
     * Determine whether the user can clone the sheet.
     *
     * @param  User  $user
     * @param  Sheet  $sheet
     * @return mixed
     *
     * @SuppressWarnings("UnusedFormalParameter")
     */
    public function clone(User $user, Sheet $sheet)
    {
        if ($sheet->allow_view == false || $sheet->allow_clone == false) {
            return $user->id === $sheet->user_id;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the sheet.
     *
     * @param  User  $user
     * @param  Sheet  $sheet
     * @return mixed
     *
     * @SuppressWarnings("UnusedFormalParameter")
     */
    public function delete(?User $user, Sheet $sheet)
    {
        return false; // for now.
    }
}
