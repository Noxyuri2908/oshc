<div class="table-responsive my-3">
    <table class="table table-md mb-0 table-dashboard fs--1">
        <thead class="bg-200 text-900">
            <tr>
                <th>Action</th>
                <th class="status">Agent</th>
                <th class="status">Processing date</th>
                <th class="status">Issue</th>
                <th class="status">Person in charge</th>
                <th class="status">Marketing support</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($marketingSupports))
                @foreach($marketingSupports as $item)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class=" btn btn-link dropdown-toggle" type="button" id="dropdownApplyReceipt{{$item->id}}"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs--1"></span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownApplyReceipt{{$item->id}}">
                                    <div class="bg-white py-2">
                                        <a class="dropdown-item edit-marketing-support-agent" data-id="{{$item->id}}" data-agent_id="{{$item->user_id}}" href="#">Edit</a>
                                        <a class="dropdown-item text-danger delete-marketing-support-agent" data-id="{{$item->id}}" data-agent_id="{{$item->user_id}}" data-url="{{route('agent.process.marketing.support.delete',['agent_id'=>$item->user_id,'marketing_support_id'=>$item->id])}}" href="#!">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{$item->getAgentName()}}</td>
                        <td>{{convert_date_form_db($item->processing_date)}}</td>
                        <td>{{$item->getIssue()}}</td>
                        <td>{{$item->getPersonName()}}</td>
                        <td style="white-space: pre;">{{$item->marketing_support}}</td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td>No data selected!
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
