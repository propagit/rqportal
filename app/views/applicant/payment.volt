{% include "applicant/header" with ['step': 3] %}

{{ content() }}

<div class="container" ng-controller="ApplicantPaymentCtrl">
    <div class="row">
        <div class="col-lg-12">

            <h3>Your Payment Details</h3>
            <p>
                Removalist Quotes will only charge you for legitimate leads that you receive.<br />
                A lead to a job in your zone is charged at a flat rate. We will auto bill your credit card once your account balance reaches $500.<br />
                To read our payment policy, <a ng-click="policy()" class="text">click here</a>.
            </p>
            <br />

            <form name="paymentForm" class="form-horizontal" novalidate>

            <div class="form-group">
                <label for="title" class="col-lg-2">Title</label>

                <div class="col-lg-2">
                    {{ elements.ewayTitle() }}
                </div>
            </div>

            <div class="form-group">
                <label for="firstname" class="col-lg-2">Card Name</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name" ng-model="form.firstname" required>
                </div>
                <div class="col-lg-2">
                    <input type="text" class="form-control" name="lastname" placeholder="Last name" ng-model="form.lastname" required>
                </div>
            </div>

            <div class="form-group">
                <label for="cardnumber" class="col-lg-2">Card Number</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="ccnumber" id="cardnumber" ng-model="form.ccnumber" required>
                </div>
            </div>

            <div class="form-group">
                <label for="expiry" class="col-lg-2">Expiry</label>
                <div class="col-lg-2">
                    {{ elements.cardMonth() }}
                </div>
                <div class="col-lg-2">
                    {{ elements.cardYear() }}
                </div>
            </div>
            <div class="form-group">
                <label for="cvv" class="col-lg-2">CVV Number</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" name="cvn" id="cvv" ng-model="form.cvn" required>
                </div>
            </div>


            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10 checkbox">
                    <label>
                        <input type="checkbox" name="agree" ng-model="form.agree" required> &nbsp; I have read and understand and agree to your <a class="text" ng-click="policy()">payment policy</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    {{ flash.output() }}
                    <button ng-click="process(form)" class="btn btn-red" ng-disabled="!paymentForm.$valid">Complete Setup</button>
                </div>
            </div>

            </form>

        </div>
    </div>
</div>

<script type="text/ng-template" id="paymentPolicy">
    <div class="modal-header"><br />
        <h3 class="modal-title" align="center">Payment Policy</h3>
    </div>
    <div class="modal-body">
        <h4 class="fw600">TERMS and CONDITIONS</h4>
        <h6 class="fw600">About us</h6>
        <p>Removalist Quote is an Australian company providing quotes to removalist companies that subscribe to our website. We offer the public a quick, easy and reliable way to contact removalist companies in their area to receive competitive quotes for their upcoming removals requirements.</p>
        <p>We pride ourselves on being fair to both removals suppliers and the customer. We provide incoming quotes to a small group of local suppliers with the goal of proving the customer with the most competitive price achievable for their upcoming move, we also aim to give each supplier a good chance of winning the work if they are competitively priced.</p>

        <h6 class="fw600">Use of Site</h6>
        <p>You may only use this site to browse the content, make legitimate purchases and shall not use this site for any other purposes, including without limitation, to make any speculative, false or fraudulent purchase. This site and the content provided in this site may not be copied, reproduced, republished, uploaded, posted, transmitted or distributed. ‘Deep-linking’, ‘embedding’ or using analogous technology is strictly prohibited. Unauthorized use of this site and/or the materials contained on this site may violate applicable copyright, trademark or other intellectual property laws or other laws.</p>

        <h6 class="fw600">Disclaimer of Warranty</h6>
        <p>The contents of this site are provided “as is” without warranty of any kind, either expressed or implied, including but not limited to warranties of merchantability, fitness for a purpose and non-infringement.</p>
        <p>The owner of this site, the authors of these contents and in general anybody connected to this site in any way, from now on collectively called “Providers”, assume no responsibility for errors or omissions in these contents.</p>
        <p>The Providers further do not warrant, guarantee or make any representation regarding the safety, reliability, accuracy, correctness or completeness of these contents. The Providers shall not be liable for any direct, indirect, general, special, incidental or consequential damages (including -without limitation- data loss, lost revenues and lost profit) which may result from the inability to use or the correct or incorrect use, abuse, or misuse of these contents, even if the Providers have been informed of the possibilities of such damages. The Providers cannot assume any obligation or responsibility.</p>
        <p>The use of these contents is forbidden in those places where the law does not allow this disclaimer to take full effect.</p>


        <h6 class="fw600">Our Rights</h6>
        <p>We reserve the right to:
        <ol>
            <li>Modify or withdraw, temporarily or permanently, the Website (or any part of) with or without notice to you and you confirm that we shall not be liable to you or any third party for any modification to or withdrawal of the Website; and/or</li>
            <li>Change these Conditions from time to time, and your continued use of the Website (or any part of) following such change shall be deemed to be your acceptance of such change. It is your responsibility to check regularly to determine whether the Conditions have been changed. If you do not agree to any change to the Conditions then you must immediately stop using the Website.</li>
            <li>We will use our reasonable endeavours to maintain the Website. The Website is subject to change from time to time. You will not be eligible for any compensation because you cannot use any part of the Website or because of a failure, suspension or withdrawal of all or part of the Website due to circumstances beyond our control.</li>
        </ol>

        <h4 class="fw600">Privacy Policy</h4>
        <p>We are committed to protecting your privacy. This privacy policy applies to all the web pages related to this website.</p>
        <p>All the information gathered in the online forms on the website is used to personally identify users that subscribe to this service. The information will not be used for anything other that which is stated in the Terms & Conditions of use for this service. None of the information will be sold or made available to anyone.</p>
        <p>The Site may collect certain information about your visit, such as the name of the Internet service provider and the Internet Protocol (IP) address through which you access the Internet; the date and time you access the Site; the pages that you access while at the Site and the Internet address of the Web site from which you linked directly to our site. This information is used to help improve the Site, analyse trends, and administer the Site.</p>
        <p>We may need to change this policy from time to time in order to address new issues and reflect changes on our site. We will post those changes here so that you will always know what information we gather, how we might use that information, and whether we will disclose that information to anyone. Please refer back to this policy regularly. If you have any questions or concerns about our privacy policy, please send us an E-mail.</p>
        <p>By using this website, you signify your acceptance of our Privacy Policy. If you do not agree to this policy, please do not use our site. Your continued use of the website following the posting of changes to these terms will mean that you accept those changes.</p>

        <h6 class="fw600">Cookie/Tracking Technology</h6>
        <p>The Site may use cookie and tracking technology depending on the features offered. Cookie and tracking technology are useful for gathering information such as browser type and operating system, tracking the number of visitors to the Site, and understanding how visitors use the Site. Cookies can also help customise the Site for visitors. Personal information cannot be collected via cookies and other tracking technology; however, if you previously provided personally identifiable information, cookies may be tied to such information. Aggregate cookie and tracking information may be shared with third parties.</p>

        <h6 class="fw600">Third Party Links</h6>
        <p>In an attempt to provide increased value to our Users, we may provide links to other websites or resources. You acknowledge and agree that we are not responsible for the availability of such external sites or resources, and do not endorse and are not responsible or liable, directly or indirectly, for the privacy practices or the content (including misrepresentative or defamatory content) of such websites, including (without limitation) any advertising, products or other materials or services on or available from such websites or resources, nor for any damage, loss or offence caused or alleged to be caused by, or in connection with, the use of or reliance on any such content, goods or services available on such external sites or resources.</p>

        <h6 class="fw600">Quote availability</h6>
        <p>Quotes will be available via your user portal when ever the site is actively online. We endevour to keep the website up at all times. We are not responsible or liable, directly or indirectly for any loss of income or inconvenience caused by the website not being accessible.</p>
        <p>Quotes are also automatically distributed to the email address you provide in your account profile. We are not responsible or liable for any loss of income caused by a supplier not being able to receive an email notification due to incorrectly entered data, undeliverable due to unforseen technical circumstances or emails ending in a junk mail folder.</p>

        <h6 class="fw600">Order processing</h6>
        <p>All quotes delivered to members (suppliers) are billed on a “per quote” basis. Quotes received are available via your member profile. The same price is allocated to a delivered quote regardless of whether the suppler successfully wins work from the quote.</p>

        <h6 class="fw600">Refunds/returns Policy (be specific)</h6>
        <p>Should you for any reason wish to cancel your purchase you will be liable for a 15% handling fee. You must advise us in writing of your cancellation and any such cancellation must be signed by the person who made the original purchase. The goods must be returned to us undamaged in the original packaging within 14 calendar days.</p>
        <p>* Note:  Jack Bones Jeans CC recommends that you use Speed Services Couriers (South African Postal Services) for all returns as it offers shipment tracking through a 24 hours customer helpline. Should you choose to use a carrier that does not offer a tracking facility and the goods are lost then no refund or return will be considered.</p>

        <p>International customers should make use of a courier that offers:</p>

        <ol>
            <li>Shipment tracking.</li>
            <li>Insure your package for safe return and declare the full value of the shipment failing which loss or damage will be for your account.</li>
        </ol>

        <h4 class="fw600">Payment Options and Pricing</h4>
        <p>All transactions will be processed in Australian Dollars (AUD).</p>
        <p>Every member account has a (AUD dollar) account balance of $0.00 when the account is first activated. Each quote sent to a member will deduct and amount from the member account balance. When a member account has a negative value of approximately $500.00 AUD an invoice will be issued for the quotes received.</p>
        <p>All members must provided an active credit card which will be automatically billed when the invoice is issued. If the automated credit card purchase is unable to go through quotes will stop being sent until the outstanding balance is brought back to a $0.00 balance.</p>
        <p>If you believe there has been an error in automatically processing your payment please contact our support team. We will not be liable for any loss of income due to a payment not automatically processing regardless of whether the fault is found to be our responsibility.</p>
        <p>If you choose to cease your membership with us an invoice will be issued for any outstanding monies that will be automatically billed on the date of cancelation.</p>
        <p>Removalist Quote reserves the right to change pricing at any time without prior notice.</p>

        <h6 class="fw600">Credit Card</h6>
        <p>We accept MasterCard and Visa credit cards. If you do not have a credit card please utilise one of the other payment options, or simply log off and return to the site at a later time to complete your member set up. All of your order details will be saved online in the “Your Profile” section available for use whenever you’re ready! Sorry for the inconvenience.</p>

        <h6 class="fw600">Security Policy</h6>
        <p>eWay process all credit card transactions. All credit card transactions are 248 bit Secure Socket Layers (SSL) encrypted. The company registration documents and the site’s registered domain name are checked and verified by GeoTrust, ensuring the cardholder and merchant that nobody can impersonate VCS to obtain confidential information.</p>
        <p>eWay is committed to providing secure online services. All encryption complies with international standards. Encryption is used to protect the transmission of personal information when completing online transactions.</p>

        <h6 class="fw600">Monitoring</h6>
        <p>We have the right, but not the obligation, to monitor any activity and content associated with the Website. We may investigate any reported violation of these Conditions or complaints and take any action that we deem appropriate (which may include, but is not limited to, issuing warnings, suspending, terminating or attaching conditions to your access and/or removing any materials from the Website).</p>

        <h6 class="fw600">Law</h6>
        <p>The Conditions will be exclusively governed by and construed in accordance with the laws of Australia whose Courts will have exclusive jurisdiction in any dispute, save that we have the right, at our sole discretion, to commence and pursue proceedings in alternative jurisdictions.</p>

        <h6 class="fw600">Updating of these Terms and Conditions</h6>
        <p>We reserve the right to change, modify, add to or remove from portions or the whole of these Terms and Conditions from time to time. Changes to these Terms and Conditions will become effective upon such changes being posted to this Website. It is the User’s obligation to periodically check these Terms and Conditions at the Website for changes or updates. The User’s continued use of this Website following the posting of changes or updates will be considered notice of the User’s acceptance to abide by and be bound by these Terms and Conditions, including such changes or updates.</p>

        <h6 class="fw600">Consent</h6>
        <p>I understand that all the designs and trademarks are registered to Removalist Quote PtyLtd and hereby accept the terms and conditions. I undertake not to copy/duplicate the trademarks and designs directly or indirectly in anyway and understand the legal implications there of. Should I be found to be in violation of this agreement I understand that I will be held liable for all legal costs incurred by Removalist Quote Pty Ltd for any civil action or any legal action deemed necessary against me.</p>


    </div>
</script>

<script type="text/ng-template" id="welcomeAboard">
    <div class="modal-header"><br />
        <h3 class="modal-title" align="center"><i class="fa fa-smile-o"></i> Welcome Aboard!</h3>
    </div>
    <div class="modal-body" id="welcome">
        <h4 align="center" class="fw600">Congratulations you have completed your account set up and will now start receiving quotes!</h4>
        <p align="center">
            Quotes will be sent to the email address specified in your profile and will also be available via your user portal.<br />
            Login to your user portal anytime using the user name and password you created at set up. <br />
            You can do the following tasks via your user portal:
        </p>
        <ul>
            <li><i class="fa fa-square-o"></i> Update and change your company profile information</li>
            <li><i class="fa fa-square-o"></i> Manage and track quotes sent to you from the system</li>
            <li><i class="fa fa-square-o"></i> Manage your location settings (where you would like to recieve jobs)</li>
            <li><i class="fa fa-square-o"></i> Manage and pay bills</li>
        </ul>
        <p align="center"><a class="btn btn-red" href="{{ baseUrl }}">Go To Portal <i class="fa fa-arrow-right"></i></a></p>
    </div>
</script>
