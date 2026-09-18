<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('') }}web/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">



    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style_a.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/form.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>

<!-- <body style="background: url(images/fullindex.jpg) no-repeat center top;"> -->

<body>
    @include('site.include.body_meta')
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start px-lg-5 -->
    @include('site.include.header')
    <!-- Navbar End -->

    <section id="testseriesDetails" class="topbannerA">
        <div class="cusContainer">
            <div class="row">
                <div class="col-lg-7">
                    <div class="testseriesDetailsLeft testseriesBoxShadow resMargin">
                        <div class="cartBlock">
                            <div class="checkOutTitle">Items in Cart</div>

                                <div class="cartProductDetails">
                                    <div class="cartProductImg">
                                        <img src="{{ asset('uploads/ranker/' . ($ranker->profile_icon ?? 'default.png')) }}" class="img-fluid">
                                    </div>
                                    <div class="cartProductInfo">
                                        @if (!empty($ranker))
                                            <div class="cartProductTitle">{{$ranker->name}}</div>
                                        @endif
                                        @if (!empty($ranker_meeting))
                                            <div class="cartProductText">{{$ranker_meeting->title}}</div>
                                        @endif
                                    </div>
                                    @if (!empty($ranker_meeting->price) && !empty($ranker_meeting->dis_price))
                                        <div class="cartProductPrice">
                                            <div class="feesPriceValue"><span>₹{{ $ranker_meeting->price }}</span> ₹{{ $ranker_meeting->dis_price }}</div>
                                        </div>
                                    @endif
                                </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-4">
                    <div class="testseriesDetailsRight testseriesBoxShadow">
                        <div class="cartBlock">
                            <div class="cartCoupon">
                                <div class="cartCouponTitle">Apply Code/Coupon</div>
                                <div class="cartCouponText">Discount code / Coupon code / Referral Code</div>
                                <div class="cartCouponBar"></div>
                                <div class="cartCouponBox">
                                    <div class="cartCouponBoxText couponText">Apply Coupon Code</div>
                                    <div class="couponBoxField couponBox couponOff">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="coupon_code" placeholder="Enter coupon code">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-applyCoupon" onclick="applyCoupon()">Apply</button>
                                            </div>
                                        </div>
                                        <div id="couponMessage" style="margin-top:10px; color: green;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="cartCoupon cartCoupon2">
                                <div class="cartCouponTitle cartCouponTitle2">Counsellor Code</div>
                                <div class="cartCouponBar"></div>
                                <div class="cartCouponBox">
                                    <div class="cartCouponBoxText counsellorText" style="color: #3561ff;">Apply Counsellor Code</div>
                                    <div class="couponBoxField counsellorBox couponOff">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="">
                                            <div class="input-group-append">
                                              <button class="btn btn-applyCoupon" style="background-color: #1e4594;">Apply</button>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="paymentSummaryAll">
                                <div class="checkOutTitle">Payment Summary</div>
                                <div class="paymentDetails">
                                    <div class="paymentInfo">Price (1 items)</div>
                                    <div class="paymentPrice" id="priceOriginal">₹{{ number_format($ranker_meeting->price, 2) }}</div>
                                </div>
                                <div class="paymentDetails">
                                    <div class="paymentInfo">Discount</div>
                                    <div class="paymentPrice" id="priceDiscount">₹{{ number_format($ranker_meeting->price - $ranker_meeting->dis_price ?? 0, 2) }}</div>
                                </div>
                                <div class="paymentDetails">
                                    <div class="paymentInfo">Shipping Cost</div>
                                    <div class="paymentPrice">₹0</div>
                                </div>
                                <div class="paymentDetails">
                                    <div class="paymentInfo">Counsellor Code</div>
                                    <div class="paymentPrice">₹0</div>
                                </div>
                                <div class="paymentDetails">
                                    <div class="paymentInfo">Coupon Discount</div>
                                    <div class="paymentPrice" id="couponDiscountAmount"><span>- ₹0</span></div>
                                </div>

                                <div class="priceBar"></div>

                                <div class="paymentDetails">
                                    <div class="paymentInfo paymentInfoTotal">Total Price</div>
                                    <div class="paymentPrice" id="priceFinal">₹{{ number_format($ranker_meeting->dis_price, 2) }}</div>
                                </div>

                                <button class="btn btn-buyNow" onclick="checkout();">Proceed to Buy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal otpPopupModal fade" id="successPopup" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form>
              <div class="check_success">
                <img src="{{ asset('') }}web/images/form/check_success.png" class="img-fluid" alt="">
              </div>
              <div class="otpPopupTitle">Awesome!</div>
              <div class="otpPopupSubTitle">Congratulations, your ranker payment successfully.</div>

              <div class="text-center">
                <a href="{{ route('testseries') }}" class="btn btnRegister mt-0">Continue</a>
              </div>
            </form>
          </div>
        </div>
    </div>

    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function(){
          $(".couponText").click(function(){
            $(this).addClass("couponOff");
            $(".couponBox").removeClass("couponOff");
          });

          $(".counsellorText").click(function(){
            $(this).addClass("couponOff");
            $(".counsellorBox").removeClass("couponOff");
          });
        });
        var finalPrice = {{ ($ranker_meeting->dis_price ?? 0) }};

        var g_data = {};
        g_data.final_price = finalPrice;
        g_data.coupon_discount = 0;
        g_data.total_discount = 0;
        g_data.coupon_code = 0;
        g_data.test_id = "{{ $ranker_meeting->id }}";
        function applyCoupon() {
            const code = document.getElementById("coupon_code").value.trim();

            if (!code) {
                alert("Please enter a coupon code.");
                return;
            }
            const originalPrice = parseFloat("{{ $ranker_meeting->price }}");
            const existingDiscount = parseFloat("{{ $ranker_meeting->price - $ranker_meeting->dis_price ?? 0 }}");

            fetch("{{ route('testseries.coupon') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ code })
            })
            .then(res => {
                if ([200, 201, 202].includes(res.status)) {
                    return res.json().then(data => ({ status: res.status, data }));
                } else {
                    throw new Error("Unexpected response status: " + res.status);
                }
            })
            .then(({ status, data }) => {
                const couponMessage = document.getElementById("couponMessage");

                if (status === 200 && data.valid) {
                    couponMessage.style.color = "green";

                    // Total discount
                    const couponType = data.type;
                    var couponDiscount;
                    var totalDiscount;

                    if(couponType == "Percentage"){
                        couponDiscount = parseFloat(data.discount);
                        couponDiscount = originalPrice * couponDiscount/ 100;
                        totalDiscount = existingDiscount + couponDiscount;
                        finalPrice = originalPrice - totalDiscount;
                    }else{
                        couponDiscount = parseFloat(data.discount);
                        totalDiscount = existingDiscount + couponDiscount;
                        finalPrice = originalPrice - totalDiscount;
                    }

                    g_data.final_price = finalPrice;
                    g_data.total_discount = totalDiscount;
                    g_data.coupon_discount = couponDiscount;
                    g_data.coupon_code = code;
                    
                    couponMessage.innerHTML = `Coupon applied! You saved ₹${couponDiscount.toFixed(2)}`;

                    document.getElementById("priceDiscount").innerText = `₹${totalDiscount.toFixed(2)}`;
                    document.getElementById("couponDiscountAmount").innerHTML = `<span>- ₹${couponDiscount.toFixed(2)}</span>`;
                    document.getElementById("priceFinal").innerText = `₹${finalPrice.toFixed(2)}`;

                    document.getElementById("priceOriginal").style.textDecoration = "line-through";

                } else if (status === 201) {
                    couponMessage.style.color = "red";
                    couponMessage.innerHTML = data.message || "Coupon has expired.";

                    document.getElementById("priceDiscount").innerText = `₹${existingDiscount.toFixed(2)}`;
                    document.getElementById("couponDiscountAmount").innerHTML = `<span>- ₹0</span>`;
                    document.getElementById("priceFinal").innerText = `₹${(originalPrice - existingDiscount).toFixed(2)}`;

                    document.getElementById("priceOriginal").style.textDecoration = "none";

                } else if (status === 202) {
                    couponMessage.style.color = "red";
                    couponMessage.innerHTML = data.message || "Invalid or inactive coupon.";

                    document.getElementById("priceDiscount").innerText = `₹${existingDiscount.toFixed(2)}`;
                    document.getElementById("couponDiscountAmount").innerHTML = `<span>- ₹0</span>`;
                    document.getElementById("priceFinal").innerText = `₹${(originalPrice - existingDiscount).toFixed(2)}`;

                    document.getElementById("priceOriginal").style.textDecoration = "none";
                }
            })
            .catch(error => {
                console.error("Error applying coupon:", error);
                alert("Error applying coupon, please try again.");
            });
        }

        function checkout(){
            console.log(finalPrice);
                var options = {
                  "key": "{{ env('RAZORPAY_KEY') }}",
                  "amount": finalPrice*100,
                  "currency": "INR",
                  "name": "{{ env('RAZORPAY_NAME') }}",
                  "description": "{{ env('RAZORPAY_DESCRIPTION') }}",
                  "image": "{{asset('')}}img/logo.png",
                  "handler": (response) => {
                        g_data.razorpay_payment_id = response.razorpay_payment_id;
                        g_data.razorpay_order_id = response.razorpay_order_id;
                        g_data.razorpay_signature = response.razorpay_signature;

                        saveData(g_data);

                   },
                  "prefill": {
                      "name": "{{Auth::user()->first_name}} {{Auth::user()->last_name}}",
                      "email": "{{Auth::user()->email_id}}",
                      "contact": "{{Auth::user()->mobile_number}}"
                  },
                  "notes": {
                      "": ""
                  },
                  "theme": {
                      "color": "#3399cc"
                  }
              };
              var razorpay = new Razorpay(options);
              razorpay.open();

              razorpay.on('payment.success', function(resp) {
                alert("Payment success.");
                alert(resp.razorpay_payment_id);
                alert(resp.razorpay_order_id);
                alert(resp.razorpay_signature);
              }); // will pass payment ID, order ID, and Razorpay signature to success handler.

              razorpay.on('payment.error', function(resp) {
                alert(resp.error.description);
              }); // will pass error object to error handler
        }

        function saveData(rData){
            rData['_token'] = "{{ csrf_token() }}";
            $.ajax({
                url: "{{ route('ranker.save_checkout') }}",
                type: "POST",
                data: rData,
                beforeSend: function() {
                    //$("#registerBtn").text("Processing...").prop("disabled", true);
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $("#successPopup").modal("show");
                    } else {
                        alert("Something went wrong. Please try again.");
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert("Something went wrong. Please try again.");
                    // Restore the button text and enable it again on error
                    // $("#registerBtn").text("Register").prop("disabled", false);
                },
                complete: function() {
                    // $("#registerBtn").text("Register").prop("disabled", false);
                }
            });
        }


    </script>
</body>

</html>
