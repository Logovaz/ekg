@section('content')
<div>
@if(Acl::has('additional_button'))
    Testing ACL for guest
@endif
</div>
@endsection

@include('common.skeleton')