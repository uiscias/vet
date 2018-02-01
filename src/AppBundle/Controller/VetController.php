<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Reminder;
use AppBundle\Entity\Consultation;
use AppBundle\Form\CsvImportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CsvImport;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Nexmo\Client as NexmoClient;
use AppBundle\Http\SpecialRequest;


//use Nexmo\Client\Credentials\Basic as NexmoClientCredentialsBasic;
//use Nexmo\Message\Text as NexmoMessageText;


class VetController extends Controller
{
    /**
     * @Route("/", name="VetHome")
     */
    public function vetAction(Request $request)
    {
        $number = 1;
        return $this->render('home.html.twig', array(
            'number' => $number,
        ));




/*
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$product->getId());
*/
    }

    /**
     * @Route("/csvImport", name="ImportCSV")
     */
    public function csvAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $uploadFile = new CsvImport();

        $formUploadFile = $this->createForm(CsvImportType::class, $uploadFile);

        $formUploadFile->handleRequest($request);

        if($formUploadFile->isSubmitted() && $formUploadFile->isValid())
        {
            $file = $formUploadFile->get('file')->getData();
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

            if(!empty($file) || 1)
            {
                $datas = $serializer->decode(file_get_contents($file), 'csv');

                foreach ($datas as $data){
//                    die(dump($data));

                    $currentClient = new Client();
                    if(isset($data["First Name"]) && $data["First Name"] != '') {
                        $currentClient->setFirstName($data["First Name"]);
                    }
                    if(isset($data["Last Name"]) && $data["Last Name"] != '') {
                        $currentClient->setLastName($data['Last Name']);
                        if ($currentClient->getFirstName() == ''){
                            $currentClient->setFirstName('  ');
                        }
                    }else{
                        if ($currentClient->getFirstName() != ''){
                            $currentClient->setLastName(' ');
                        }else{
                            continue;
                        }
                    }
                    $currentClient->setAssociatedUsername($user);
                    if($data['Home Street'] != ''){
                        $currentClient->setAddress($data['Home Street']);
                        if($data['Home City'] != '') {
                            $currentClient->setCity($data['Home City']);
                        }
                        if($data['Home Postal Code'] != '') {
                            $currentClient->setCP($data['Home Postal Code']);
                        }

                    }else if($data['Business Street'] != ''){
                        $currentClient->setAddress($data['Business Street']);
                        if($data['Business City'] != '') {
                            $currentClient->setCity($data['Business City']);
                        }
                        if($data['Business Postal Code'] != '') {
                            $currentClient->setCP($data['Business Postal Code']);
                        }
                    }else if($data['Other Street'] != ''){
                        $currentClient->setAddress($data['Other Street']);
                        if($data['Other City'] != '') {
                            $currentClient->setCity($data['Other City']);
                        }
                        if($data['Other Postal Code'] != '') {
                            $currentClient->setCP($data['Other Postal Code']);
                        }
                    }

                    $phone1 = '';
                    $phone2 = '';


                    if($data['Mobile Phone'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Mobile Phone'];
                            }
                        }else{
                            $phone1 = $data['Mobile Phone'];
                        }
                    }
                    if($data['Primary Phone'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Primary Phone'];
                            }
                        }else{
                            $phone1 = $data['Primary Phone'];
                        }
                    }
                    if($data['Home Phone'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Home Phone'];
                            }
                        }else{
                            $phone1 = $data['Home Phone'];
                        }
                    }
                    if($data['Home Phone 2'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Home Phone 2'];
                            }
                        }else{
                            $phone1 = $data['Home Phone 2'];
                        }
                    }
                    if($data['Business Phone'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Business Phone'];
                            }
                        }else{
                            $phone1 = $data['Business Phone'];
                        }
                    }
                    if($data['Business Phone 2'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Business Phone 2'];
                            }
                        }else{
                            $phone1 = $data['Business Phone 2'];
                        }
                    }
                    if($data['Other Phone'] != ''){
                        if ($phone1 != ''){
                            if ($phone2 != ''){}else{
                                $phone2 = $data['Business Phone'];
                            }
                        }else{
                            $phone1 = $data['Business Phone'];
                        }
                    }

                    $mail1 = '';
                    if(isset($data['E-mail Address']) && $data['E-mail Address'] != ''){
                        $currentClient->setEMail($data['E-mail Address']);
                    }else if(isset($data['E-mail 2 Address']) && $data['E-mail 2 Address'] != ''){
                        $currentClient->setEMail($data['E-mail 2 Address']);
                    }else if(isset($data['E-mail 3 Address']) && $data['E-mail 3 Address'] != ''){
                        $currentClient->setEMail($data['E-mail 3 Address']);
                    }

                    if ($phone1 != '')
                        $currentClient->setPhone($phone1);
                    if ($phone2 != '')
                        $currentClient->setPhone2($phone2);

                    $currentClient->setLastConsultationToNow();
                    $currentClient->setContactPreferences('All');

                    $importedConsult = new Consultation();
                    $importedConsult->setClient($currentClient);
                    $importedConsult->setTitre('Consultation Importée de Outlook');
                    if(isset($data['Notes']))
                        $importedConsult->setNotes($data['Notes']);
                    $importedConsult->setHasDebts(false);
                    $importedConsult->setDebtValueForThisConsultation(0);



                    $em = $this->getDoctrine()->getManager();
                    $em->persist($currentClient);
                    $em->persist($importedConsult);
                    $em->flush($currentClient);
                    $em->flush($importedConsult);


                }

            }
        }


        return $this->render('importCSV.html.twig', array(
            'formUploadFile' => $formUploadFile->createView(),
        ));




    }
/*
    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        // ... do something, like pass the $product object into a template
    }


$repository = $this->getDoctrine()->getRepository('AppBundle:Product');

// query for a single product by its primary key (usually "id")
$product = $repository->find($productId);

// dynamic method names to find a single product based on a column value
$product = $repository->findOneById($productId);
$product = $repository->findOneByName('Keyboard');

// dynamic method names to find a group of products based on a column value
$products = $repository->findByPrice(19.99);

// find *all* products
$products = $repository->findAll();





$repository = $this->getDoctrine()->getRepository('AppBundle:Product');

// query for a single product matching the given name and price
$product = $repository->findOneBy(
    array('name' => 'Keyboard', 'price' => 19.99)
);

// query for multiple products matching the given name, ordered by price
$products = $repository->findBy(
    array('name' => 'Keyboard'),
    array('price' => 'ASC')
);








public function updateAction($productId)
{
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('AppBundle:Product')->find($productId);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$productId
        );
    }

    $product->setName('New product name!');
    $em->flush();

    return $this->redirectToRoute('homepage');
}






$em->remove($product);
$em->flush();




$em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM AppBundle:Product p
    WHERE p.price > :price
    ORDER BY p.price ASC'
)->setParameter('price', 19.99);

$products = $query->getResult();






   * Matches /blog/*
     *
     * @Route("/blog/{slug}", name="blog_show")
     * /
    public function showAction($slug)
    {
        // $slug will equal the dynamic part of the URL
        // e.g. at /blog/yay-routing, then $slug='yay-routing'

        // ...
    }
 */


    protected  function sendSms($number, $content){
//        $client = new NexmoClient(new NexmoClientCredentialsBasic('c6ef9c85', '9961df892795dd28'));
//        $text = new NexmoMessageText($number, 'NEXMO' , $content);
//        $content = urlencode($content);
        $data = array("username" => "semias", "handle" => "ae844ce49f8f34fe3ea79c7980ead561", "userid" => "13882", "msg" => $content,  "from" => "BudgetSMS", "to" => $number);
//        $response = $client->request('GET', "sendsms/?username=semias&handle=ae844ce49f8f34fe3ea79c7980ead561&userid=13882&msg=".$content."&from=BudgetSMS&to=$number");
        $curl = curl_init();
        $c_url = "https://api.budgetsms.net/sendsms/";
//        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $c_url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);

        curl_close($curl);



//        $request = Request::createFromGlobals();
//        $content = $request->getContent();

//        $response = http_get("https://api.budgetsms.net/sendsms/?username=semias&handle=ae844ce49f8f34fe3ea79c7980ead561&userid=13882&msg=".$content."&from=BudgetSMS&to=$number", array("timeout"=>1), $info);
//        print_r($info);
        return $result;
    }

    protected  function sendReminderMail($mail, $subject, $content){
        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($subject)
            ->setTo($mail)
            ->setFrom('rappel.dr.thielens@gmail.com')
            ->setBody(
            //                 $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
            //                     'Emails/registration.html.twig',
                               //      array('name' => $name)
                $content
                //),
               // 'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(S
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        return $mailer->send($message);
    }

    protected  function notifyReminderSent($user, $clientName, $reminderType){

        $reminderTYpeMessage = '';
        switch ($reminderType) {
            case 'ALL':
                $reminderTYpeMessage = "Un rappel de vaccin a été envoyé sur tous les canaux (tel1, tel2 et mail) a ".$clientName;
                break;
            case 'eMail':
                $reminderTYpeMessage = "Un rappel de vaccin a été envoyé par mail a ".$clientName;
                break;
            case 'Phone1':
                $reminderTYpeMessage = "Un rappel de vaccin a été envoyé sur le téléphone 1 a ".$clientName;
                break;
            case 'Phone2':
                $reminderTYpeMessage = "Un rappel de vaccin a été envoyé sur le téléphone 2 a ".$clientName;
                break;
            case 'Phone1AndEMail':
                $reminderTYpeMessage = "Un rappel de vaccin a été envoyé sur le téléphone 1 et par mail a ".$clientName;
                break;
        }

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($reminderTYpeMessage)
            ->setTo($user)
            ->setFrom('rappel.dr.thielens@gmail.com')
            ->setBody(
            //                 $this->renderView(
            // app/Resources/views/Emails/registration.html.twig
            //                     'Emails/registration.html.twig',
            //      array('name' => $name)
                $reminderTYpeMessage
            //),
            // 'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        return $mailer->send($message);
    }

    protected  function tagReminderAsSent($reminderID)
    {
        $reminder = $this->getDoctrine()
            ->getRepository(Reminder::class)
            ->find($reminderID)
        ;
        $reminder->setSent(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush($reminder);
    }

        /**
     * @Route("/reminderJob", name="reminderJob")
     */
    public function reminderJobAction(Request $request)
    {
        $msg = '';
        $em = $this->getDoctrine()->getManager();
        $querySumDebts = $em->createQueryBuilder('remindersOfTheDay')
            ->from('AppBundle\Entity\Reminder', 'r')
            ->addselect('IDENTITY(r.client)')
            ->addselect('r.id')
            ->addselect('r.media')
            ->addselect('IDENTITY(r.consultation)')
            ->addselect('IDENTITY(r.animal)')
            ->addselect('r.title')
            ->addselect('r.note')
            ->addselect('c.firstName')
            ->addselect('c.lastName')
            ->addselect('a.name')
            ->addselect('c.eMail')
            ->addselect('c.phone')
            ->addselect('c.phone2')
            ->addselect('u.email as usmail')
            ->addselect('us.reminderMessage as reminderMessage')
            ->leftJoin('r.client', 'c')
            ->leftJoin('r.animal', 'a')
            ->leftJoin(
                'AppBundle\Entity\UserSettings',
                'us',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.associatedUsername = us.username')
            ->leftJoin(
                'AppBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'u.username= us.username')
            ->leftJoin('r.consultation', 'cons')
            ->where('DATE_DIFF(r.reminderDateTime, CURRENT_DATE()) <= 0  and r.enabled = 1 and r.sent = 0')

            ->getQuery();

        $reminders = $querySumDebts->getResult();
        $mailResult = '';
        $smsResult = '';
        $sms2Result = '';

        foreach ($reminders as $reminder){
            $contentSMS = "Bonjour, la vaccination annuelle de rappel de votre animal de compagnie est a effectuer. Presentez-vous
avec lui et son carnet des que possible. Cordiales salutations. Dr veterinaire Thielens.";
            $content = str_replace( array(
                                            '[CLIENT_NAME]',
                                            '[CLIENT_FIRSTNAME]',
                                            '[REMINDER_NOTE]',
                                            '[ANIMAL_NAME]',
            ),
                array(
                    $reminder['lastName'],
                    $reminder['firstName'],
                    $reminder['note'],
                    $reminder['name'],
                ), $reminder['reminderMessage'] );

            $reminder['phone'] = preg_replace('/^0/', '+32', preg_replace("/[^0-9]/", "", $reminder['phone']));
            $reminder['phone2'] = preg_replace('/^0/', '+32', preg_replace("/[^0-9]/", "", $reminder['phone2']));

            switch ($reminder['media']) {
                case 'ALL':
                    if (isset($reminder['eMail']) and $reminder['eMail'] != ''){
                        $mailResult = $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    }
                    try {
                        if (isset($reminder['phone']) and $reminder['phone'] != ''){
                            $smsResult = $this->sendSms($reminder['phone'], $contentSMS);
                        }
                    } catch (NexmoClient\Exception\Exception $e) {
                        $msg .= $e->getMessage();
                        $smsResult = false;
                    }
                    try {
                        if (isset($reminder['phone2']) and $reminder['phone2'] != ''){
                            $sms2Result = $this->sendSms($reminder['phone2'], $contentSMS);
                        }
                    } catch (NexmoClient\Exception\Exception $e) {
                        $msg .= $e->getMessage();
                        $sms2Result = false;
                    }


                    if($mailResult || $smsResult || $sms2Result){
                        $this->tagReminderAsSent($reminder['id']);
                    }
                    break;
                case 'eMail':
                    if (isset($reminder['eMail']) and $reminder['eMail'] != '') {
                        $mailResult = $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    }
                    if($mailResult){
                        $this->tagReminderAsSent($reminder['id']);
                    }
                    break;
                case 'Phone1':
                    if (isset($reminder['phone']) and $reminder['phone'] != '') {
                        try {
                            $smsResult = $this->sendSms($reminder['phone'], $contentSMS);
                        } catch (NexmoClient\Exception\Exception $e) {
                            $msg .= $e->getMessage();
                            $smsResult = false;
                        }
                    }
                    if($smsResult){
                        $this->tagReminderAsSent($reminder['id']);
                    }
                    break;
                case 'Phone2':
                    if (isset($reminder['phone2']) and $reminder['phone2'] != '') {
                        try {
                            if (isset($reminder['phone']) and $reminder['phone'] != ''){
                                $sms2Result = $this->sendSms($reminder['phone2'], $contentSMS);
                            }
                        } catch (NexmoClient\Exception\Exception $e) {
                            $msg .= $e->getMessage();
                            $sms2Result = false;
                        }
                    }
                    if($sms2Result){
                        $this->tagReminderAsSent($reminder['id']);
                    }
                    break;
                case 'Phone1AndEMail':
                    if (isset($reminder['eMail']) and $reminder['eMail'] != ''){
                        $mailResult = $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    }
                    if(isset($reminder['phone']) and $reminder['phone'] != '') {
                        try {
                            if (isset($reminder['phone']) and $reminder['phone'] != ''){
                                $smsResult = $this->sendSms($reminder['phone'], $contentSMS);
                            }
                        } catch (NexmoClient\Exception\Exception $e) {
                            $msg .= $e->getMessage();
                            $smsResult = false;
                        }

                    }
                    if($mailResult || $smsResult){
                        $this->tagReminderAsSent($reminder['id']);
                    }
                    break;
            }
            if($mailResult != ''){
                $msg .= '<br /> reminder (mail) sent to '.$reminder['usmail'].': '.$reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'].' ==> '.$this->notifyReminderSent($reminder['usmail'], $reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'], 'eMail', $mailResult );
            }else{
            }
            if($smsResult != ''){
                $msg .= '<br /> reminder (sms) sent to '.$reminder['phone'].': '.$reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'].' ==> '.$this->notifyReminderSent($reminder['usmail'], $reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'], 'Phone1', $smsResult);
            }else{
            }
            if($sms2Result != '' ){
                $msg .= '<br /> reminder (sms2) sent to '.$reminder['phone2'].': '.$reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'].' ==> '.$this->notifyReminderSent($reminder['usmail'], $reminder['firstName'].' '.$reminder['firstName'].' pour '.$reminder['name'], 'Phone2', $sms2Result);
            }else{
            }

        }

        return $this->render('home.html.twig', array(
            'reminders' => $reminders,
            'output' => $msg,
        ));
    }

}