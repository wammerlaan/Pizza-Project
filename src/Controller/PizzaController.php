<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Pizza;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\PizzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

class PizzaController extends AbstractController
{
    /**
     * @Route("/", name="pizza_homepage")
     */
    public function homepage(CategoryRepository $CategoryRepository)
    {
        $categories = $CategoryRepository ->findAll();
        return $this->render('pizza/home.html.twig',
            ['categories' => $categories]);
    }

    /**
     * @return Response
     * @Route("/pizza/{id}", name="pizza_showpizzas")
     */

    public function pizza(int $id, PizzaRepository $PizzaRepository)
    {
        $pizzas = $PizzaRepository->findBy(array('category' => $id));
        return $this->render('pizza/pizzas.html.twig',
        ['pizzas' => $pizzas]);
    }

    /**
     * @Route("/contact", name="pizza_contact")
     */
    public function contact()
    {
        return $this->render('pizza/contact.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     * @Route("/order/{id}", name="pizza_order");
     */
    public function order(Request $request, Pizza $pizza, EntityManagerInterface $em){
        $pizzaName = $pizza->getName();

//        $entityManager = $em->getManager();

        $order = new Order();

        $order->setPizza($pizza);

        $order->setStatus("in progress...");

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Uw pizza wordt bereid.');

            return $this->redirectToRoute('pizza_homepage');
        }
        return $this->renderForm('pizza/order.html.twig', [
            'pizza' => $pizzaName,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/orders", name="pizza_showorders")
     */
    public function showOrders(OrderRepository $orderRepository, PizzaRepository $pizza)
    {
        $orders = $orderRepository ->findAll();
        return $this->render('pizza/allOrders.html.twig',
            ['orders' => $orders]);
    }
}