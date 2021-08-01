        <form method="post" action="<?php echo $this->get_option( 'posturl' ) ?>">
             <input type="hidden" name="MbrId" value="<?php echo $args['MbrId'] ?>">
             <input type="hidden" name="MerchantID" value="<?php echo $args['MerchantID'] ?>">
             <input type="hidden" name="UserCode" value="<?php echo $args['UserCode'] ?>">
             <input type="hidden" name="UserPass" value="<?php echo $args['UserPass'] ?>">
             <input type="hidden" name="SecureType" value="<?php echo $args['SecureType'] ?>">
             <input type="hidden" name="TxnType" value="<?php echo $args['TxnType'] ?>">
             <input type="hidden" name="InstallmentCount" value="<?php echo $args['InstallmentCount'] ?>">
             <input type="hidden" name="Currency" value="<?php echo $args['Currency'] ?>">
             <input type="hidden" name="OkUrl" value="<?php echo $args['OkUrl'] ?>">
             <input type="hidden" name="FailUrl" value="<?php echo $args['FailUrl'] ?>">
             <input type="hidden" name="OrderId" value="<?php echo $args['OrderId'] ?>">
             <input type="hidden" name="OrgOrderId" value="<?php echo $args['OrgOrderId'] ?>">
             <input type="hidden" name="PurchAmount" value="<?php echo $args['PurchAmount'] ?>">
             <input type="hidden" name="Lang" value="<?php echo $args['Lang'] ?>">
             <input type="hidden" name="TemplateType" value="<?php echo $args['TemplateType'] ?>">
             <input type="hidden" name="ShippingNameSurname" value="<?php echo $args['ShippingNameSurname'] ?>">
             <input type="hidden" name="ShippingEmail" value="<?php echo $args['ShippingEmail'] ?>">
             <input type="hidden" name="ShippingPhone" value="<?php echo $args['ShippingPhone'] ?>">
             <input type="hidden" name="ShippingCompanyName" value="<?php echo $args['ShippingCompanyName'] ?>">
             <input type="hidden" name="ShippingAddress" value="<?php echo $args['ShippingAddress'] ?>">
             <input type="hidden" name="ShippingTown" value="<?php echo $args['ShippingTown'] ?>">
             <input type="hidden" name="ShippingCity" value="<?php echo $args['ShippingCity'] ?>">
             <input type="hidden" name="ShippingZipCode" value="<?php echo $args['ShippingZipCode'] ?>">
             <input type="hidden" name="ShippingCountry" value="<?php echo $args['ShippingCountry'] ?>">
             <input type="hidden" name="BillingNameSurname" value="<?php echo $args['BillingNameSurname'] ?>">
             <input type="hidden" name="BillingEmail" value="<?php echo $args['BillingEmail'] ?>">
             <input type="hidden" name="BillingPhone" value="<?php echo $args['BillingPhone'] ?>">
             <input type="hidden" name="BillingCompanyName" value="<?php echo $args['BillingCompanyName'] ?>">
             <input type="hidden" name="BillingAddress" value="<?php echo $args['BillingAddress'] ?>">
             <input type="hidden" name="BillingTown" value="<?php echo $args['BillingTown'] ?>">
             <input type="hidden" name="BillingCity" value="<?php echo $args['BillingCity'] ?>">
             <input type="hidden" name="BillingZipCode" value="<?php echo $args['BillingZipCode'] ?>">
             <input type="hidden" name="BillingCountry" value="<?php echo $args['BillingCountry'] ?>">
             <input type="hidden" name="Rnd" value="<?php echo $args['Rnd'] ?>">
             <input type="hidden" name="Hash" value="<?php echo $args['Hash'] ?>">
             <input type="submit" name="submit" id="post-button" value="Pay Now">
        </form>