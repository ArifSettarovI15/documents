<?php


namespace App\Modules\Clients\Repositories;


use App\Modules\Clients\Models\ClientsEmailsModel;
use Illuminate\Database\Eloquent\Collection;

class ClientEmailsRepository
{
    public static function save_emails($request): bool
    {
        try {
            (new ClientsEmailsModel)->where(
                'email_client_id',
                $request->email_client_id
            )->delete();
        } catch (\Exception $e) {
        }

        foreach ($request->email_email as $key=>$email)
        {
            $emails = new ClientsEmailsModel();
            $emails->email_client_id = $request->email_client_id;
            $emails->email_name = $request->email_name[$key];
            $emails->email_position = $request->email_position[$key];
            $emails->email_email = $email;
            $emails->save();
        }
        return true;
    }
    public static function get_emails($id): Collection
    {
        return (new ClientsEmailsModel)->where(
        'email_client_id',$id)->get();
    }

}
