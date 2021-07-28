@foreach($data as $tmp)
    @php
        $invoice = $tmp->invoice;
        if(!empty($invoice)){
            $providerCom = $invoice->getProviderCom();
        $agent = $invoice->agent;
        $hh =(!empty($tmp->invoice))?$tmp->invoice->hoahong:'';
        $sum_amount = $invoice->phieuthus->sum('amount');
        $currency = !empty($tmp->invoice) && $tmp->invoice->provider != null ? $tmp->invoice->provider->currency() : '';
        $sum_bank_fee =  !empty($tmp->invoice) && $tmp->invoice->phieuthus != null ?$tmp->invoice->phieuthus->sum('bank_fee'):'';
        $_total_amount = !empty($invoice)?floatval($invoice->net_amount) + floatval($invoice->bank_fee) +  floatval($invoice->surcharge) - floatval($invoice->promotion_amount) + floatval($invoice->extra):'';

        $phieuthu_old_exchange_rate = (!empty($invoice) && floatval($invoice->net_amount + $sum_bank_fee) != 0)?round(floatval((floatval($sum_amount)/floatval($invoice->net_amount + $sum_bank_fee))), 2):0;

        if($hh != null && !empty($invoice)){
                    $payment_note = $hh->payment_note_provider;
                    $text_com = null;
                    if($payment_note == 1){
                    $comm = $invoice->getCom();
                    if($comm != null){
                        $text_com =  $comm->comm;
                        $donvi = $comm->donvi;
                        $text_donvi = $donvi == 1 ? '%' : '$';
                    }
                    }

                    if($payment_note == 1) $provider_com = $providerCom->textCom();
                    else $provider_com = '100%';


                    if($providerCom->type == 1){
                    switch ($payment_note) {
                        case '1':
                            $re_total_amount = number_format(floatval($invoice->net_amount)*floatval($providerCom->amount)/100);
                            break;
                        default:
                            $re_total_amount = number_format($providerCom->amount);
                            break;
                    }
                    }else{
                    $re_total_amount = number_format(floatval($invoice->net_amount));
                    }

                }
        }

    @endphp
    <tr class="data-profit" id="data-profit_{{$tmp->id}}" data-id="{{(!empty($invoice))?$invoice->id:''}}">
        <td class="align-middle sticky-col">
            <input class="ml-3 sub_chk" data-id="{{(!empty($tmp->invoice))?$tmp->invoice->id:''}}" data-id-profit="{{(!empty($tmp))?$tmp->id:''}}" type="checkbox"
                   aria-label="Checkbox for tdis row"/>
        </td>
        <td class="align-middle sticky-col second-col">
            <div class="dropdown text-sans-serif">
                <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal mr-3 btn-dropdown-z-index"
                        type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fas fa-ellipsis-h fs--1">
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    {{-- <a class="dropdown-item invoice_info" style="cursor: pointer;" data-id="{{(!empty($tmp->invoice))?$tmp->invoice->id:''}}">View</a> --}}
                    @can('profitInvoice.edit')
                        <a class="dropdown-item profit_data_edit"  data-url_edit="{{route('ajax.customer.showData',['tab'=>'profit','id'=>$tmp->id])}}"
                           href="{{route('customer.process.index',['id'=>(!empty($tmp->invoice))?$tmp->invoice->id:'', 'tab'=>4,'tab_link'=>4])}}">Edit</a>
                    @endcan
                    {{-- <a class="dropdown-item" href="{{route('customer.process.index',['id'=>(!empty($tmp->invoice))?$tmp->invoice->id:'', 'tab'=>1])}}" target="_blank">Process</a> --}}
                    {{-- <a class="dropdown-item export_invoice" data-id="{{(!empty($tmp->invoice))?$tmp->invoice->id:''}}" style="cursor: pointer;">Export Invoice</a> --}}
                    @can('profitInvoice.delete')
                        <a class="dropdown-item text-danger" id="on_delete_data" data-type="profit"
                           data-url="{{route('crm.ajax.deleteProfit',['id'=>$tmp->id])}}" data-id="{{$tmp->id}}"
                           href="#!">Delete</a>
                    @endcan
                </div>
            </div>
        </td>
        <td class="align-middle sticky-col">{{(!empty($tmp->invoice))?$tmp->invoice->ref_no:''}}</td>
        <td class="align-middle sticky-col"><a style="cursor: pointer; color: red" class="agent_info"
                                               data-id={{(!empty($tmp->invoice)) && $tmp->invoice->agent != null ? $tmp->invoice->agent->id : ''}}>{{(!empty($tmp->invoice)) && $tmp->invoice->agent != null ? $tmp->invoice->agent->name : ''}}</a>
        </td>

        <td class="align-middle sticky-col">{{(!empty($tmp->invoice) && !empty($tmp->invoice->agent))?$tmp->invoice->agent->country():''}}</td>
        <td class="align-middle sticky-col">{{!empty($tmp->invoice) && $tmp->invoice->registerCus() != null ? $tmp->invoice->registerCus()->first_name." ".$tmp->invoice->registerCus()->last_name : ''}}</td>
        <td class="align-middle sticky-col">{{!empty($tmp->invoice) && $tmp->invoice->provider != null ? $tmp->invoice->provider->name : ''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice) && isset(config('myconfig.status_invoice')[$tmp->invoice->status]) ? config('myconfig.status_invoice')[$tmp->invoice->status] : ''}}</td>
        <td class="align-middle">{{!empty($hh)?$hh->policy_no:''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice) && $tmp->invoice->service != null ? $tmp->invoice->service->name : ''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice)?$tmp->invoice->visaName():''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice)?$tmp->invoice->policyName():''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice)?\Carbon::parse($tmp->invoice->start_date)->format('d/m/Y'):''}}</td>
        <td class="align-middle">{{!empty($tmp->invoice)?\Carbon::parse($tmp->invoice->end_date)->format('d/m/Y'):''}}</td>
        <td class="align-middle">{{$tmp->statusVisaText()}}</td>
        <td class="align-middle">{{$tmp->visa_month}}</td>
        <td class="align-middle">{{$tmp->visa_year}}</td>

        <!-- Profit 1 -->
        <td style="background-color: #bfffff" title="Profit 1">{{convert_price_float($tmp->profit_money)}}</td>
        <td style="background-color: #bfffff" title="Profit 1">{{convert_price_float($tmp->profit_money_VND)}}</td>
        <td style="background-color: #bfffff" title="Profit 1">{{$tmp->profit_status == 1 ? "Done" : "Refund"}}</td>
        <td style="background-color: #bfffff" title="Profit 1">{{$tmp->comm_status == 1 ? "Done" : "Refund"}}</td>
        <td style="background-color: #bfffff" title="Profit 1">{{convert_price_float($tmp->profit_exchange_rate)}}</td>
        <td style="background-color: #bfffff" title="Profit 1">{{convert_price_float($tmp->profit_extra_money)}}</td>
        <!-- Profit 1 -->

        <!-- Annalink received -->
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($tmp->invoice->net_amount):''}} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($tmp->invoice->promotion_amount):''}} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($tmp->invoice->surcharge):''}} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($sum_bank_fee):'' }} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($tmp->invoice->extra):''}} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{!empty($tmp->invoice)?convert_price_float($_total_amount):''}} {{$currency}}</td>
        <td style="background-color: #bfbfff"
            title="Annalink received ">{{convert_price_float($phieuthu_old_exchange_rate)}}</td>
        <td style="background-color: #bfbfff" title="Annalink received ">{{convert_price_float($sum_amount)}} VNĐ</td>
        <!-- Annalink received -->

        <!-- Pay commission for User/Cousellor -->
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{(!empty($comm) && !empty($text_donvi))?$comm->comm.' '.$text_donvi:''}}</td>
        <td style="background-color: #fffe98" title="Pay commission for Agent/Cousellor">{{$tmp->pay_agent_bonus}}</td>
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{convert_price_float($tmp->pay_agent_amount_comm)}}</td>
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{convert_price_float($tmp->pay_agent_deduction)}}</td>
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{convert_price_float($tmp->pay_agent_exchange_rate)}}</td>
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{convert_price_float($tmp->pay_agent_amount_VN)}}</td>
        <td style="background-color: #fffe98"
            title="Pay commission for Agent/Cousellor">{{\Carbon::parse($tmp->pay_agent_date)->format('d/m/Y')}}</td>
        <td style="background-color: #fffe98" title="Pay commission for Agent/Cousellor">{{!empty($agent) && $agent->gst == 1 ? 'Included' : 'Not included '}}</td>
        <!-- Pay commission for User/Cousellor -->

        <!-- Commission received from provider -->
        <td style="background-color: #ffbfff"
            title="Commission received from provider">{{(!empty($provider_com))?$provider_com:''}}</td>
        <td style="background-color: #ffbfff"
            title="Commission received from provider">{{convert_price_float($tmp->re_total_amount)}}</td>
        <td style="background-color: #ffbfff"
            title="Commission received from provider">{{convert_price_float($tmp->exchange_rate_re_provider)}}</td>
        <td style="background-color: #ffbfff"
            title="Commission received from provider">{{convert_price_float($tmp->re_total_amount_vn)}}</td>
        <td style="background-color: #ffbfff"
            title="Commission received from provider">{{\Carbon::parse($tmp->date_of_receipt)->format('d/m/Y')}}</td>
        <td style="background-color: #ffbfff" title="Commission received from provider">{{$tmp->note_of_receipt}}</td>
        <!-- Commission received from provider -->

        <!-- Pay for provider -->
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_amount) }}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ isset(config('myconfig.bank_fee')[$tmp->pay_provider_bank_fee]) ? config('myconfig.bank_fee')[$tmp->pay_provider_bank_fee] : ''}}</td style="background-color: #81d881" title="Pay for provider">
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_total_amount) }}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_exchange_rate) }}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_total_VN) }}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_paid)}}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ convert_price_float($tmp->pay_provider_balancer_1)}}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ !empty($payment_note) && !empty(config('myconfig.payment_note_provider')[$payment_note]) ? config('myconfig.payment_note_provider')[$payment_note] : ''}}</td>
        <td style="background-color: #81d881"
            title="Pay for provider">{{ !empty($tmp->pay_provider_date)?\Carbon::parse($tmp->pay_provider_date)->format('d/m/Y'):'' }}</td>
        <td style="background-color: #81d881" title="Pay for provider">{{ $tmp->pay_provider_bank_account }}</td>
    </tr>
@endforeach

