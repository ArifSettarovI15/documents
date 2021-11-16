<?php


namespace App\Modules\Dashboard\Repositories;


use App\Modules\Settings\Models\ActionsLogModel;
use CloudConvert\CloudConvert;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class DashboardRepository
{
    public function check_smtp_server(): bool
    {
        $target = "smtp.mail.ru";
        $port = env('MAIL_OPEN_PORT');
        $timeout = 9;
        try {
            $con = fsockopen($target, $port, $errno, $errstr, $timeout);
        } catch (\Exception $e) {
            return false;
        }
        if ($con !== false) {
            $res = fgets($con, 1024);
            if ($res != '' && strpos($res, '220') === 0) {
                fclose($con);

                return true;
            }
        }
        fclose($con);

        return false;
    }

    public function check_convertion_server(): array
    {
        try {
            $cloudconvert = new CloudConvert(
                [
                    'api_key' => env('CLOUDCONVERT_API_KEY'),
                    'sandbox' => false,
                ]
            );
            try {
                $user = $cloudconvert->users()->me();
                $credits = $user->getCredits();

                if ($credits !== 0) {
                    $result = true;
                } else {
                    $result = 'warning';
                }

                return ['status' => $result, 'credits' => $credits];
            } catch (\Exception $e) {
                return ['status' => false];
            }
        } catch (\Exception $e) {
            return ['status' => false];
        }
    }

    /**
     * @param int $per_page
     * @param array|null $filters
     * @return LengthAwarePaginator
     */
    public function get_actions_paginated_filtered(int $per_page, array $filters=null): LengthAwarePaginator
    {

        $actions = ActionsLogModel::query();
        if ($filters)
        {
            foreach ($filters as $field_name => $filter)
            {
                if ($field_name != 'page') {
                    if ($field_name == 'log_time') {
                        $filter['value'] = strtotime($filter['value']);
                    }
                    if ($filter['type'] == 'search') {
                        $actions->where($field_name, 'LIKE', '%' . $filter['value'] . '%');
                    } elseif ($filter['type'] == 'filter') {
                        $actions->where($field_name, '=', $filter['value']);
                    } elseif ($filter['type'] == 'less') {
                        $actions->where($field_name, '<=', $filter['value']);
                    } elseif ($filter['type'] == 'more') {
                        $actions->where($field_name, '>=', $filter['value']);
                    }
                }
            }
        }
        $actions->orderBy('log_time', 'DESC');
        return $actions->paginate($per_page);
    }
}
