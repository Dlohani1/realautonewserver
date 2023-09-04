<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
            
<table class="table">
    <thead>
      <tr>
        <th>Sl.no</th> 
        <th>Assigned On</th>
        <th>Project</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Status</th>
        <th>Comments</th>
      </tr>
    </thead>
    <tbody>
    @foreach($leads as $value) 

      <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$value->lead_assigned_on}}</td>
        <td>{{$value->project_name}}</td>
        <td>{{$value->name}}</td>
        <td>{{$value->mobile_no}}</td>
         <td>{{$value->mail_id}}</td>
        @if($value->lead_status == 0)
        <td>In Progress</td>
        @elseif($value->lead_status == 1)
        <td>Hot</td>
        @elseif($value->lead_status == 2)
        <td>Close</td>
        @elseif($value->lead_status == 3)
        <td>Site Visit</td>
        @elseif($value->lead_status == 4)
        <td>Fake Lead</td>
        @elseif($value->lead_status == 5)
        <td>Out of Location</td>
        @elseif($value->lead_status == 6)
        <td>Not Interested</td>
        @elseif($value->lead_status == 7)
        <td>Wrong No</td>
        @elseif($value->lead_status == 8)
        <td>Not Reachable</td>
        @endif

        <?php
          if (isset($value->comments))  {
            foreach($value->comments as $key1 => $comment) {
              echo "<td>".$comment['comment_date']."</td><td>".$comment['comment']."</td>";
            }
        }  
        ?>
        
      </tr>
    @endforeach
    </tbody>
  </table>

</body>
</html>
