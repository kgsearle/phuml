<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Configuration\ClassDiagramBuilder;
use PhUml\Configuration\ClassDiagramConfiguration;
use PhUml\Parser\CodebaseDirectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command will generate a UML class diagram by reading an OO codebase
 *
 * This command has 2 required arguments
 *
 * 1. `directory`. The path where your codebase lives
 * 2. `output`. The path to where the generated `png` image will be saved
 *
 * There are 3 options
 *
 * 1. `processor`. The command to be used to create the `png` image, it can be either `neato` or `dot`
 *    This is the only required option
 * 2. `recursive`. If present it will look recursively within the `directory` provided
 * 3. `associations`. If present the command will generate associations to the classes/interfaces
 *    injected through the constructor and the attributes of the class
 */
class GenerateClassDiagramCommand extends GeneratorCommand
{
    /** @throws \InvalidArgumentException */
    protected function configure()
    {
        $this
            ->setName('phuml:diagram')
            ->setDescription('Generate a class diagram scanning the given directory')
            ->setHelp(
                <<<HELP
Example:
    php bin/phuml phuml:diagram -r -a -p neato ./src out.png

    This example will scan the `./src` directory recursively for php files.
    It will process them with the option `associations` set to true. After that it 
    will be send to the `neato` processor and saved to the file `out.png`.
HELP
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the class diagram'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your class diagram'
            )
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory recursively'
            )
            ->addOption(
                'processor',
                'p',
                InputOption::VALUE_REQUIRED,
                'Choose between the neato and dot processors'
            )
            ->addOption(
                'associations',
                'a',
                InputOption::VALUE_NONE,
                'If present, the Graphviz processor will generate association among classes'
            )
            ->addOption(
                'hide-private',
                'i',
                InputOption::VALUE_NONE,
                'If present, no private attributes or methods will be processed'
            )
            ->addOption(
                'hide-protected',
                'o',
                InputOption::VALUE_NONE,
                'If present, no protected attributes or methods will be processed'
            )
            ->addOption(
                'hide-methods',
                'm',
                InputOption::VALUE_NONE,
                'If present, no methods will be processed'
            )
            ->addOption(
                'hide-attributes',
                't',
                InputOption::VALUE_NONE,
                'If present, no attributes will be processed'
            )
            ->addOption(
                'hide-empty-blocks',
                'b',
                InputOption::VALUE_NONE,
                'If present, no empty blocks will be shown'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codebasePath = $input->getArgument('directory');
        $classDiagramPath = $input->getArgument('output');

        $builder = new ClassDiagramBuilder(new ClassDiagramConfiguration($input->getOptions()));

        $codeFinder = $builder->codeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from($codebasePath));

        $classDiagramGenerator = $builder->classDiagramGenerator();
        $classDiagramGenerator->attach($this->display);

        $classDiagramGenerator->generate($codeFinder, $classDiagramPath);

        return 0;
    }
}
