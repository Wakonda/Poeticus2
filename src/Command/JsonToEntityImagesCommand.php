<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Poem;
use App\Entity\PoemImage;

class JsonToEntityImagesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:json-to-entity';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
		parent::__construct();
        $this->em = $em;
    }
	
    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$poems = $this->em->getRepository(Poem::class)->findAll();
		
		foreach($poems as $poem) {
			$images = $poem->getImages();
			
			if(!empty($images)) {
				foreach($images as $image) {
					$poemImage = new PoemImage();
					
					$poemImage->setImage($image);
					$poemImage->setPoem($poem);
					$poem->addPoemImage($poemImage);
					
					$this->em->persist($poem);
					$this->em->persist($poemImage);
				}
				$this->em->flush();
			}
		}
    }
}