# FlashPaymentRequester-QR
Request payments via QR codes.

The address the request is sent to can be given via GET or POST parameters (address/amount).
It can also be added manually with the $address and $amount variable on the first few lines.

Allowing the GET and POST parameters is possible after setting line 4 ($allowRequestParameters) to True instead of the default False.

String format
'flashcoin:[address]?amount=[amount]&message=[message]'
