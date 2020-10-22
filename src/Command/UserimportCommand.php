<?php

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use League\Csv\Reader;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;
use App\Entity\Delegacion;

class UserimportCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;

    }

    protected function configure()
    {
        $this
            ->setDescription('Import Users using CSV file')
            ->setName("csv:userimport")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Intentando importar usuarios");
        

        $encoder = new BCryptPasswordEncoder(12);

        $reader = Reader::createFromPath('%kernel.project_dir%/../src/CSV/users.csv','r');
        $reader->setHeaderOffset(0);
        $reader->setDelimiter(";");
        $result = $reader->getRecords();
        $io->progressStart(iterator_count($result));
        foreach($result as $row)
        {
            $user = $this->em->getRepository(Usuario::class)->findOneBy(array("username"=>$row["Usuario"]));
            $delegacion = $this->em->getRepository(Delegacion::class)->find($row["Clinica"]);
            if(!$user)
            {
                $user = new Usuario();
            }
            $user->setNombre($row["Nombre"]);
            $user->setApellidos($row["Primer_Apellido"]." ".$row["Segundo_Apellido"]);
            $user->setUsername($row["Usuario"]);
            $user->setDelegacion($delegacion);
            $user->setPassword($encoder->encodePassword($row["Pass"],null));
            $this->em->persist($user);
            $this->em->flush();
            $io->progressAdvance();
        }

        $io->success('All went Well');
    }
}
