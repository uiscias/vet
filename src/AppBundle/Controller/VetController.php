<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Consultation;
use AppBundle\Form\CsvImportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CsvImport;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;


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
        echo ("sms sent to ".$number);
    }

    protected  function sendReminderMail($mail, $subject, $content){
        echo ("mail sent to ".$mail);
        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($subject)
            ->setTo($mail)
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
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $mailer->send($message);
        return null;
    }

    /**
     * @Route("/reminderJob", name="reminderJob")
     */
    public function reminderJobAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $querySumDebts = $em->createQueryBuilder('remindersOfTheDay')
            ->from('AppBundle\Entity\Reminder', 'r')
            ->addselect('IDENTITY(r.client)')
            ->addselect('r.media')
            ->addselect('IDENTITY(r.consultation)')
            ->addselect('IDENTITY(r.animal)')
            ->addselect('r.title')
            ->addselect('r.note')
            ->addselect('us.reminderMessage')
            ->addselect('c.eMail')
            ->addselect('c.phone')
            ->addselect('c.phone2')
            ->addselect('a.name')
            ->leftJoin('r.client', 'c')
            ->leftJoin('r.animal', 'a')
            ->leftJoin(
                'AppBundle\Entity\UserSettings',
                'us',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.associatedUsername = us.username')
            ->leftJoin('r.consultation', 'cons')
            ->where('DATE_DIFF(r.reminderDateTime, CURRENT_DATE()) <= 0  and r.enabled = 1 and r.sent = 0')
//            ->setParameter('user', $user)
            ->getQuery();

        $reminders = $querySumDebts->getResult();


        foreach ($reminders as $reminder){
            $content = $reminder['reminderMessage'] . ' ' . $reminder['name'];
            switch ($reminder['media']){
                case 'ALL':
                    if(isset($reminder['eMail']) and $reminder['eMail'] != '')
                        $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    if(isset($reminder['phone']) and $reminder['phone'] != '')
                        $this->sendSms($reminder['phone'], $content);
                    if(isset($reminder['phone2']) and $reminder['phone2'] != '')
                        $this->sendSms($reminder['phone2'], $content);
                    break;
                case 'eMail':
                    if(isset($reminder['eMail']) and $reminder['eMail'] != '')
                        $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    break;
                case 'Phone1':
                    if(isset($reminder['phone']) and $reminder['phone'] != '')
                        $this->sendSms($reminder['phone'], $content);
                    break;
                case 'Phone2':
                    if(isset($reminder['phone2']) and $reminder['phone2'] != '')
                        $this->sendSms($reminder['phone2'], $content);
                    break;
                case 'Phone1AndEMail':
                    if(isset($reminder['eMail']) and $reminder['eMail'] != '')
                        $this->sendReminderMail($reminder['eMail'], $reminder['title'], $content);
                    if(isset($reminder['phone']) and $reminder['phone'] != '')
                        $this->sendSms($reminder['phone'], $content);
                    break;
            }
        }

        $number = 1;
        return $this->render('home.html.twig', array(
            'reminders' => $reminders,
        ));




        /*

9
down vote
It is also possible to use built-in function DATE_DIFF(date1, date2) which returns difference in days. Check docs

$result = $this->createQueryBuilder('l')
    ->where('DATE_DIFF(l.startDate, CURRENT_DATE()) = 0')


        $qb->select('p')
   ->where('YEAR(p.postDate) = :year')
   ->andWhere('MONTH(p.postDate) = :month')
   ->andWhere('DAY(p.postDate) = :day');

$qb->setParameter('year', $year)
   ->setParameter('month', $month)
   ->setParameter('day', $day);


        $today_startdatetime = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 00:00:00") );

        */
    }

}