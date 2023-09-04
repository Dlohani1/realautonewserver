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
        <th><strong>Sl.no</strong></th>
          <th><strong>Source</strong></th>
          <th><strong>Date Assigned</strong></th>
          <th><strong>Project</strong></th>
          <th><strong>Status</strong></th>
          <th><strong>Name</strong></th>
          <th><strong>Mobile</strong></th>
          <th><strong>Email</strong></th>

        @for($x = 1; $x <= $commentTotal; $x++)
          <th style="color:red;font-weight: 700;font-size: 13px;"> Date </th>
          <th style="color:green;font-weight: 700;font-size: 13px;">Comments</th>
        @endfor
      </tr>
    </thead>
    <tbody>
    @foreach($leads as $value) 

      <tr>
        <td style="text-align: center;">{{$loop->iteration}}</td>
	<td>{{$value->source}}</td>
        <td>{{date("jS F, Y",strtotime($value->lead_assigned_on))}}</td>
        <td>{{$value->project_name}}</td>
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
        <td>{{ucfirst($value->name)}}</td>
        <td>{{$value->mobile_no}}</td>
        <td>{{$value->mail_id}}</td>
       

        <?php
          if (isset($value->comments))  {
            foreach($value->comments as $key1 => $comment) {
              echo "<td style='color:red'>". $comment['comment_date']  ."  By " 
.ucfirst($comment['comment_by'])."</td><td 
style='color:green'>".$comment['comment']."</td>"; 
            }  
        }  
        ?>
        
      </tr>
    @endforeach
    </tbody>
  </table>




</body>
</html>

