@section('title')
[% $title %]
@endsection

@section('content')
  <div class="info-block">
    <h2>[% $profile->first_name . ' ' . $profile->last_name . ' (' . $profile->login . ') - ' . $profile->state %] </h2>          
    <hr>
    [[% Form::open(array('action' => 'UserController@userChange')) %]]
    <table class="admin-user-info">
      <tr>
        <th colspan="2">User info
        </th>
      </tr>
      <tr>
        <td class="label">First name</td>
        <td class="info">
          <input type="text" name="first_name" value="[% $profile->first_name %]">
        </td>
      </tr>
      <tr>
        <td class="label">Middle name</td>
        <td class="info">
          <input type="text" name="middle_name" value="[% $profile->middle_name %]">
        </td>
      </tr>
      <tr>
        <td class="label">Last name</td>
        <td class="info">
          <input type="text" name="last_name" value="[% $profile->last_name %]">
        </td>
      </tr>
      <!-- <tr>
        <td class="label">Email</td>
        <td class="info">
          <input type="text" name="email" value="[% $profile->email %]">
        </td>
      </tr> -->
      <tr>
        <th class="info" colspan="2" align="left">Profile properties
        </th>
      </tr>
      <tr>
        <td class="label">Login</td>
        <td class="info">
          <input type="text" name="login" value="[% $profile->login %]">
        </td>
      </tr>
      <tr>
        <td class="label">State</td>
        <td class="info">
          <select name="state">
            <option value="registered" 
              @if($profile->state == 'registered') 
                selected="selected" 
              @endif
            >registered</option>
            <option value="information" 
              @if($profile->state == 'information') 
                selected="selected" 
              @endif
            >information</option>
            <option value="confirmation" 
              @if($profile->state == 'confirmation') 
                selected="selected" 
              @endif
            >confirmation</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class="label">Role</td>
        <td class="info">
          <select name="role">
            <option value="admin" 
              @if($profile->role == 'admin') 
                selected="selected" 
              @endif
            >admin</option>
            <option value="user" 
              @if($profile->role == 'user') 
                selected="selected" 
              @endif
            >user</option>
            <option value="doctor" 
              @if($profile->role == 'doctor') 
                selected="selected" 
              @endif
            >doctor</option>
          </select>
        </td>
      </tr>
      <tr>
        <th class="info" colspan="2" align="left">
      	  <input type="submit" value="[% Lang::get('locale.save') %]" class="green-btn signup-btn">
      	</th>
      </tr>
    </table>
    [[% Form::close() %]]
      <!-- <div class="photo-block">
      </div> -->
  </div>
@endsection

@include('control.sidebar')
@include('common.skeleton')