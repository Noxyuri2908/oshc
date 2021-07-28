@foreach($data as $tmp)
    @php
        $invoice = $tmp->invoice;
        $agent = $invoice->agent;
        $profit = $invoice->profit->first();
        $refund = $invoice->refund->first();

        $providerCom = $invoice->getProviderCom();
        if($profit != null && $agent != null && $providerCom != null && $refund != null){
        $_total_amount = floatval($invoice->net_amount) + floatval($invoice->bank_fee) +  floatval($invoice->surcharge) - floatval($invoice->promotion_amount) + floatval($invoice->extra);
        $sum_amount = $invoice->phieuthus->sum('amount');
        $sum_bank_fee =  $invoice->phieuthus->sum('bank_fee');
        $phieuthu_old_exchange_rate = round(floatval((floatval($sum_amount)/floatval($invoice->net_amount + $sum_bank_fee))), 2);
        $currency = $invoice->provider != null ? $invoice->provider->currency() : '';
        $hh = $invoice->hhs->first();
        if($hh != null){
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
        ;
        }
        }

    @endphp

    @if($agent != null && isset($hh) && isset($comm) && $hh != null && $comm != null && $providerCom != null && $refund != null && $profit != null)
        <tr class="data-refund" id="data-refund_{{$tmp->id}}" data-id="{{$invoice->id}}">
            <td class="align-middle sticky-col">
                <input class="ml-3 sub_chk" data-id="{{$tmp->id}}" type="checkbox" aria-label="Checkbox for tdis row" />
            </td>
            <td class="align-middle sticky-col second-col">
                <div class="dropdown text-sans-serif">
                    <button class="btn btn-link text-600 btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fas fa-ellipsis-h fs--1"></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @can('refundInvoice.edit')
                            <a class="dropdown-item refund_data_edit" data-id="{{$tmp->id}}" data-url_edit="{{route('ajax.customer.showData',['tab'=>'refund','id'=>$tmp->id])}}"
                               href="{{route('customer.process.index',['id'=>$invoice->id, 'tab'=>5,'tab_link'=>5])}}">Edit</a>
                        @endcan
                        {{-- <a class="dropdown-item" href="{{route('customer.process.index',['id'=>$invoice->id, 'tab'=>1])}}" target="_blank">Process</a> --}}
                        {{-- <div class="dropdown-divider"> </div> --}}
                        {{-- <a class="dropdown-item export_invoice" data-id="{{$invoice->id}}" style="cursor: pointer;">Export Invoice</a> --}}
                        {{-- <div class="dropdown-divider"> </div> --}}
                        @can('refundInvoice.delete')
                            <a class="dropdown-item text-danger " id="on_delete_data" data-type="refund"
                               data-url="{{route('crm.ajax.deleteRefund',['id'=>$tmp->id])}}" data-id="{{$tmp->id}}"
                               href="#!">Delete</a>
                        @endcan
                    </div>
                </div>
            </td>
            <td class="align-middle sticky-col"><a>{{$invoice->ref_no}}</a></td>
            <td class="align-middle sticky-col"><a style="cursor: pointer; color: red" class="agent_info"
                                                   data-id={{$invoice->agent != null ? $invoice->agent->id : ''}}>{{$invoice->agent != null ? $invoice->agent->name : ''}}</a>
            </td>
            <td class="align-middle sticky-col">{{(!empty($tmp->invoice) && !empty($tmp->invoice->agent))?$tmp->invoice->agent->country():''}}</td>
            <td class="align-middle sticky-col">{{!empty($tmp->invoice) && $tmp->invoice->registerCus() != null ? $tmp->invoice->registerCus()->first_name." ".$tmp->invoice->registerCus()->last_name : ''}}</td>
            <td class="align-middle sticky-col">{{!empty($tmp->invoice) && $tmp->invoice->provider != null ? $tmp->invoice->provider->name : ''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice) && isset(config('myconfig.status_invoice')[$tmp->invoice->status]) ? config('myconfig.status_invoice')[$tmp->invoice->status] : ''}}</td>
            <td class="align-middle">{{!empty($tmp->refund_type_of_refund_pp) ? $tmp->getTypeOfRefund() : ''}}</td>
            <td class="align-middle">{{!empty($hh)?$hh->policy_no:''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice) && $tmp->invoice->service != null ? $tmp->invoice->service->name : ''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice)?$tmp->invoice->visaName():''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice)?$tmp->invoice->policyName():''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice)?\Carbon::parse($tmp->invoice->start_date)->format('d/m/Y'):''}}</td>
            <td class="align-middle">{{!empty($tmp->invoice)?\Carbon::parse($tmp->invoice->end_date)->format('d/m/Y'):''}}</td>
            <td class="align-middle">{{(!empty($tmp->invoice) && !empty($profit) && !empty($profit->statusVisaText()))?$profit->statusVisaText():''}}</td>
            <td class="align-middle">{{(!empty($tmp->invoice) && !empty($profit))?$profit->visa_month:''}}</td>
            <td class="align-middle">{{(!empty($tmp->invoice) && !empty($profit))?$profit->visa_year:''}}</td>

            <!-- Profit 1 -->
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit))?convert_price_float($profit->profit_money):''}}</td>
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit))?convert_price_float($profit->profit_money_VND):''}}</td>
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit) && $profit->profit_status == 1)? "Done" : "Refund"}}</td>
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit) && $profit->comm_status == 1 )? "Done" : "Refund"}}</td>
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit))?convert_price_float($profit->profit_exchange_rate):''}}</td>
            <td style="background-color: #bfffff"
                title="Profit 1">{{(!empty($tmp->invoice) && !empty($profit))?convert_price_float($profit->profit_extra_money):''}}</td>
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
            <td style="background-color: #bfbfff" title="Annalink received ">{{convert_price_float($sum_amount)}}VNĐ
            </td>
            <!-- Annalink received -->

            <!-- Pay commission for User/Cousellor -->
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{$comm->comm.' '.$text_donvi}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?$profit->pay_agent_bonus:''}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?convert_price_float($profit->pay_agent_amount_comm):''}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?convert_price_float($profit->pay_agent_deduction):''}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?convert_price_float($profit->pay_agent_exchange_rate):''}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?convert_price_float($profit->pay_agent_amount_VN):''}}</td>
            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{(!empty($profit))?\Carbon::parse($profit->pay_agent_date)->format('d/m/Y'):''}}</td>

            <td style="background-color: #fffe98"
                title="Pay commission for Agent/Cousellor">{{$agent->gst == 1 ? 'Included' : 'Not include '}}</td>
            <!-- Pay commission for User/Cousellor -->

            <!-- Commission received from provider -->
            <td style="background-color: #ffbfff" title="Commission received from provider">{{$provider_com}}</td>
            <td style="background-color: #ffbfff"
                title="Commission received from provider">{{(!empty($profit))?convert_price_float($profit->re_total_amount):''}}</td>
            <td style="background-color: #ffbfff"
                title="Commission received from provider">{{(!empty($profit))?convert_price_float($profit->exchange_rate_re_provider):''}}</td>
            <td style="background-color: #ffbfff"
                title="Commission received from provider">{{(!empty($profit))?convert_price_float($profit->re_total_amount_vn):''}}</td>
            <td style="background-color: #ffbfff"
                title="Commission received from provider">{{(!empty($profit))?\Carbon::parse($profit->date_of_receipt)->format('d/m/Y'):''}}</td>
            <td style="background-color: #ffbfff"
                title="Commission received from provider">{{(!empty($profit))?$profit->note_of_receipt:''}}</td>
            <!-- Commission received from provider -->

            <!-- Pay for provider -->
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_amount):'' }}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit)) && isset(config('myconfig.bank_fee')[$profit->pay_provider_bank_fee]) ? config('myconfig.bank_fee')[$profit->pay_provider_bank_fee] : ''}}</td style="background-color: #81d881" title="Pay for provider">
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_total_amount):'' }}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_exchange_rate):'' }}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_total_VN):'' }}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_paid):''}}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?convert_price_float($profit->pay_provider_balancer_1):''}}</td>
            {{--            <td style="background-color: #81d881"--}}
            {{--                title="Pay for provider">{{ $invoice->provider != null ? $invoice->provider->name : ''}}</td>--}}
            <td style="background-color: #81d881"
                title="Pay for provider">{{ isset(config('myconfig.payment_note_provider')[$payment_note]) ? config('myconfig.payment_note_provider')[$payment_note] : ''}}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?\Carbon::parse($profit->pay_provider_date)->format('d/m/Y'):'' }}</td>
            <td style="background-color: #81d881"
                title="Pay for provider">{{ (!empty($profit))?$profit->pay_provider_bank_account:'' }}</td>


            <!-- Provider paid -->
            @php
                $providerPaidAmountVND = $refund->refund_provider_amount * $refund->refund_provider_exchange_rate;
            @endphp
            <td>{{(!empty($refund))?convert_price_float($refund->refund_provider_amount):'' }}</td>
            <td>{{(!empty($refund))?convert_price_float($refund->refund_provider_exchange_rate):'' }}</td>
            <td>{{$providerPaidAmountVND != null ? convert_price_float($providerPaidAmountVND) : ''}}</td>
            <td>{{$refund != null ? \Carbon::parse($refund->refund_provider_date)->format('d/m/Y') : ''}}</td>
            <!-- Provider paid -->


            <!-- Pay back student -->
            @php
                $amountVNDPayBackStudent = $refund->std_amount * $refund->std_exchange_rate;
            @endphp
            <td>{{(!empty($refund))?convert_price_float($refund->std_amount):''}}</td>
            <td>{{(!empty($refund))?convert_price_float($refund->std_deduction):''}}</td>
            <td>{{(!empty($refund))?convert_price_float($refund->std_exchange_rate):''}}</td>
            <td>{{(!empty($amountVNDPayBackStudent))?convert_price_float($amountVNDPayBackStudent):''}}</td>
            <td>{{(!empty($refund))?\Carbon::parse($refund->std_date_apyment)->format('d/m/Y'):''}}</td>
            <td>{{(!empty($refund))?$refund->std_note:''}}</td>
            <!-- Pay back student -->

            <!-- Get back com form agent -->
            <td>{{(!empty($refund))?convert_price_float($refund->refund_amount_com_agent):''}}</td>
            <td>{{(!empty($refund))?convert_price_float($refund->refund_exchange_rate_agent):''}}</td>
            <td>{{(!empty($refund))?convert_price_float($refund->refund_agent_vnd):''}}</td>
            <td>{{(!empty($refund))?$refund->note2:''}}</td>

            <!-- Get back com form agent -->

            <!-- Profit 2 -->
            <td>{{ (!empty($refund))?\Carbon::parse($refund->request_date)->format('d/m/Y'):'' }}</td>
            <td>{{ isset(config('myconfig.status_refund')[$refund->std_status]) ? config('myconfig.status_refund')[$refund->std_status] : '' }}</td>
            <td>{{ (!empty($refund))?convert_price_float($refund->refund_profit_2):'' }}</td>
            <td>{{ (!empty($refund))?convert_price_float($refund->refund_profit_2_VN):'' }}</td>
            <!-- Profit 2 -->
        </tr>
    @endif
@endforeach
