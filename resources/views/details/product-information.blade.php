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
          <h2> About Us</h2>
          <p>RealAuto is a Real-Estate Marketing Automation with CRM that integrates with your website, helping you capture leads, convert them into clients, and retain them by 
providing better service than any other company in your niche. It enables you to educate your clients/customers with automated messages via WhatsApp, SMS and Email.
</p>
          
<p>It is a complete automation follow-up system to increase your site visit, reduce your cost and make the real estate business easy for you. It helps you contact all visitors of your 
website and configure follow-up messages at any time with a control panel.
</p>

<p> RealAuto is owned and operated by its parent company Active Digital Technology. This first-of-its-kind Automation Platform for Real Estate co-founded two gentlemen, Ayan & 
Garrett, in 2020. They are passionate about Proptech and want to revolutionize the real estate industry through real-time data and automation.</p>
</div>
</div>
</div>
</section>

@endsection
