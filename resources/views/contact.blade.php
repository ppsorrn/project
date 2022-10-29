@extends('layouts.main')

@section('title', 'Contact')

@section('content')

<main>
    <h1>Contact</h1>
    <table>
        <tr>
            <th><b>Tel.</b></th>
            <td>: 053920299</td>
        </tr>
        <tr>
            <th><b>Facebook</b></th>
            <td>: CAMT College of Arts, Media and Technology </td>
        </tr>
        <tr>
            <th><b>Website</b></th>
            <td>: <a href="https://www.camt.cmu.ac.th/index.php/th/">www.camt.cmu.ac.th</a></td>
        </tr>
        <tr>
            <th><b>Address</b></th>
            <td>: 239 Huaykaew Rd., Suthep, Muang, Chiang Mai 50200</td>
        </tr>
        <tr>           
            <div class="map">
            <th><b>Google Map</b></th>
                <!-- Google Map Copied Code -->
                <td>:
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3776.9483795796614!2d98.94821451489693!3d18.80045368724654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da3a6bf542deb3%3A0x85fbac3033920444!2sCollege%20of%20Arts%2C%20Media%20and%20Technology%2C%20Chiang%20Mai%20University!5e0!3m2!1sen!2sth!4v1666356080938!5m2!1sen!2sth" 
                    width="500" 
                    height="250" 
                    style="border:0;" 
                    allowfullscreen="auto" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </td>
            </div>
        </tr>
    </table>
</main>

@endsection