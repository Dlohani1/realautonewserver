@extends('web.master')
@section('content')
<style>
	  body {
	   font-family: 'Poppins', sans-serif;
      }
	  .privacy_policy h2{
		color: #f47b20;
		padding: 25px 0px;
		text-align: center;
		font-size: 30px;
        font-weight: 600;
     }
	 .privacy_policy h2:after{
		 content:"";
		 width:80px;
		 height:2px;
		 background-color: #f47b20;
		 margin: 8px auto;
         display: block;
	}
	 .privacy_policy h4{
		 color: #000; 
		 font-size: 20px;
		 margin-top: 25px;
	 }
	 .privacy_policy p{
		 color: #4e4e4e; 
		 font-size: 16px;
	 }
	 .t-yelo{color: #fba200 !important; }
	</style>
<section class="privacy_policy">
    <div class="container">
      <div class="row">
        <div class="col-md-12 mb-5">
          <h2>Privacy Policy</h2>
          <h4>Introduction :</h4>
          <p>Your data stored in RealAuto is protected by default and can only be seen by you or the individuals you specifically choose to share the data with (such as when you email 
content to your customers).</p>
          
<p>Our services are based on monthly subscriptions with no hidden fees or clauses. None of your leads, contacts, data, or details will ever be sold or exposed to advertisers that would 
conflict with your interests.
</p>
<h4>Privacy :</h4>
<p>The data you provide to RealAuto will never be sold, rented, shared, or made accessible to any third parties. You will always retain complete control of your data and decide when to 
delete it.
</p>
<h4>Security:</h4>
<p>To ensure the security and privacy of your data, we encrypt all data in transit (TLS 1.2 via HTTPS) and the database (256-bit Advanced Encryption Standard). We're committed to using 
the highest-grade technology to safeguard your data.</p>
        </div>
      </div>
    </div>
  </section>

@endsection
