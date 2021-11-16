<?php

namespace App\Modules\Invoices\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Invoices\Repositories\InvoiceRepository;
use App\Modules\Invoices\Requests\CustomInvoiceRequest;
use App\Repositories\GlobalRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoicesController extends Controller
{
    /**
     * @var InvoiceRepository $repo
     */
    protected $repo;
    protected $breadcrumbs;


    /**
     * InvoicesController constructor.
     * @var $repo InvoiceRepository
     */
    public function __construct()
    {
        $this->repo = new InvoiceRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.invoices.index'), 'text'=>'Счета'];
    }

    /**
     * @return view
     */
    public function index():view
    {
        $contracts = $this->repo->get_contracts();
        $invoices = $this->repo->get_not_sent_invoices_to_view();
        return view('Invoices::index', compact('invoices', 'contracts'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function history(Request $request)
    {
        $contracts = $this->repo->get_contracts();
        if ($request->ajax())
        {
            $invoices = $this->repo->get_invoices_history_to_view($request->json()->all());
            return response()->json(['html'=>view('Invoices::history_table', compact('invoices', 'contracts'))->render(),
                                     'paging'=>view('paging')->with('paginator', $invoices)->render()]);
        }

        $clients = GlobalRepository::get_active_clients_list();
        $invoices = $this->repo->get_invoices_history_to_view();
        $this->breadcrumbs[] = ['text'=>'История'];
        return view('Invoices::history', compact('invoices', 'contracts', 'clients'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function not_payed(Request $request)
    {
        $contracts = $this->repo->get_contracts();
        if ($request->ajax()){
            $invoices = $this->repo->get_invoices_not_payed_to_view($request->json()->all());
            return response()->json(['html'=>view('Invoices::not_payed', compact('invoices', 'contracts'))->render(),
                                     'paging'=>view('paging')->with('paginator', $invoices)->render()
                                    ]);
        }

        $invoices = $this->repo->get_invoices_not_payed_to_view();
        $this->breadcrumbs[] = ['text'=>'Не оплаченные'];
        return view('Invoices::not_payed', compact('invoices', 'contracts'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param CustomInvoiceRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create_custom_invoice(CustomInvoiceRequest $request): JsonResponse
    {
        $result = $this->repo->create_custom_invoice($request);
        if ($result === true)
        {
            return response()->json(['status'=>true,
                              'message'=>'Счет поставлен в очередь на отправку']);
        }
        return response()->json(['redirect'=>$result,
                                 'status'=>true,
                                 'message'=>'Счет будет скачан в течении 10 секунд']);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return Application|Factory|View|JsonResponse
     */
    public function client_invoices(Request $request, int $id)
    {
        $contracts = $this->repo->get_contracts();
        if ($request->ajax())
        {

            $invoices = $this->repo->get_invoices_history_to_view($request->json()->all());
            return response()->json(['html'=>view('Invoices::history_table', compact('invoices', 'contracts'))->render(),
                                     'paging'=>view('paging')->with('paginator', $invoices)->render()
                                    ]);
        }
        $invoices = $this->repo->get_client_invoices_history_to_view($id);
        $this->breadcrumbs[] = ['text'=>'Счета клиента '.$id];
        return view('Invoices::history', compact('invoices', 'contracts'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $invoice = $this->repo->get_invoice($id);

        $this->breadcrumbs[] = ['text'=>'Счет #'.$id];
        return view('Invoices::view', compact('invoice'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param $id
     * @return BinaryFileResponse
     */
    public function file($id): BinaryFileResponse
    {
        return response()->download($this->repo->get_invoice_file($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function status(Request $request): JsonResponse
    {
        $result = $this->repo->change_status($request);

        if ($result == true) {
            if ($request->value == '0' and $request->field = 'invoice_send_active') {
                $this->repo->model->where('invoice_id', $request->id)->update(['invoice_sended' => 2]);
            }

            if (isset($request->value) and $request->value == '0') {
                return response()->json(['status' => true, 'message' => 'Отправка счета отменена']);
            }

            if (isset($request->value) and $request->value == '1') {
                return response()->json(['status' => true, 'message' => 'Отправка счета активирована']);
            }

            return response()->json(['status' => true, 'message' => 'Статус отправки счета изменен']);
        }

        if (isset($request->value) and $request->value == '0') {
            return response()->json(['status' => true, 'message' => 'Не удалось отменить отправку счета']);
        }

        if (isset($request->value) and $request->value == '1') {
            return response()->json(['status' => true, 'message' => 'Не удалось активировать отправку счета']);
        }

        return response()->json(
            ['status' => true, 'message' => 'Не удалось изменить статус отправки счета']
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function redo_send(int $id): JsonResponse
    {
        $result = $this->repo->redo_invoice_send($id);
        if ($result) {
            return response()->json(['status' => true, 'message' => 'Счет добален в очередь отправки']);
        }

        return response()->json(['status' => false, 'message' => 'Не удалось активировать отправку счета']);
    }
    /**
     * @param int $id
     * @return JsonResponse
     */
    public function off_send(int $id): JsonResponse
    {
        $result = $this->repo->off_invoice_send($id);
        if ($result) {
            return response()->json(['status' => true, 'message' => 'Отправка счета отменена']);
        }

        return response()->json(['status' => false, 'message' => 'Не удалось отменить отправку счета']);
    }
    /**
     * @param int $id
     * @return JsonResponse
     */
    public function check_payed(int $id): JsonResponse
    {
        $result = $this->repo->check_invoice_payed($id);
        if ($result) {
            return response()->json(['status' => true, 'message' => 'Счет помечен как оплаченный']);
        }

        return response()->json(['status' => false, 'message' => 'Не удалось изменить оплату счета']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create_invoice_by_contract (Request $request): JsonResponse
    {
        return response()->json(['file'=>$this->repo->create_invoice_by_contract($request->contract)]);
    }


    /**
     * @return bool
     */
    public function create_invoices():bool
    {
        $this->repo->create_invoices();
        return true;
    }


    /**
     * @return bool
     */
    public function update_plan():bool
    {
        $this->repo->update_plans();
        return true;
    }


    /**
     * @return bool
     */
    public function send_invoices_queue():bool
    {
        $this->repo->send_invoices_queue();
        return true;
    }

    /**
     * @return bool
     */
    public function check_notPayed():bool
    {
        $this->repo->send_invoices_check_payed();
        return true;
    }

    /**
     * @return bool
     */
    public function send_notPayed_after5days():bool
    {
        $this->repo->send_not_payed_invoices_after_5_days_queue();
        return true;
    }
}
