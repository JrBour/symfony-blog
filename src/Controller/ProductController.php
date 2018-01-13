<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;

class ProductController extends Controller
{
  /**
  * @Route("/product", name="Product")
  **/
  public function index()
  {
    $em = $this->getDoctrine()->getManager();

    $product = new Product();
    $product->setName('Keyboard');
    $product->setPrice(19.99);

    // tell Doctrine you want to (eventually) save the Product (no queries yet)
    $em->persist($product);

    // Execute the query (INSERT …)
    $em->flush();

    return new Response('Saved new product with id : ' . $product->getId());
  }
}



 ?>
