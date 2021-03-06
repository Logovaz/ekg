@section('title')
[% $title %]
@endsection

@section('content')

  <div class="photo-block">

  </div>
  <div class="info-block">
    <h2>[% $profile->first_name . 
            ' ' . $profile->last_name . 
            ' (' . $profile->login . 
            ') - ' . $profile->state %] </h2>          
    <hr>
    <table>
      <tr>
        <th colspan="2">User info
        </th>
      </tr>
      <tr>
        <td class="label">First name</td>
        <td class="info">[% $profile->first_name %]</td>
      </tr>
      <tr>
        <td class="label">Middle name</td>
        <td class="info">[% $profile->middle_name %]</td>
      </tr>
      <tr>
        <td class="label">Last name</td>
        <td class="info">[% $profile->last_name %]</td>
      </tr>
      <tr>
        <td class="label">Email</td>
        <td class="info">[% $profile->email %]</td>
      </tr>
      <tr>
        <td class="label">Gender</td>
        <td class="info">[% $profile->gender %]</td>
      </tr>
      <tr>
        <th colspan="2" align="left">Profile properties
        </th class="info">
      </tr>
      <tr>
        <td class="label">Login</td>
        <td class="info">[% $profile->login %]</td>
      </tr>
      <tr>
        <td class="label">State</td>
        <td class="info">[% $profile->state %]</td>
      </tr>
      <tr>
        <td class="label">Registered</td>
        <td class="info">[% $profile->registered %]</td>
      </tr>
      <tr>
        <td class="label">Language</td>
        <td class="info">[% $profile->lang %]</td>
      </tr>
      <tr>
        <td class="label">Email</td>
        <td class="info">[% $profile->email %]</td>
      </tr>
      <tr>
        <td class="label">Email</td>
        <td class="info">[% $profile->email %]</td>
      </tr>
    </table>
  </div> 

@endsection

@include('control.sidebar')
@include('common.skeleton')