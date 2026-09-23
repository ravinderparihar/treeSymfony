<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:seed-trees', description: 'Seed Himalayan trees with local names, uses and categories')]
class SeedTreesCommand extends Command
{
    private const LONG_TEXT_LIMIT = 255;

    public function __construct(
        private readonly Connection $connection,
        #[Autowire('%kernel.project_dir%/data/seed/himalayan_trees.php')]
        private readonly string $dataFile,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would be added without writing');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');
        $data = require $this->dataFile;
        $generic = $data['generic'];

        $this->connection->beginTransaction();
        try {
            $added = 0;
            foreach ($data['trees'] as $t) {
                if ($this->treeExists($t['sci'], $t['en'])) {
                    $io->writeln("skip  {$t['en']} ({$t['sci']}) - already exists");
                    continue;
                }

                $row = [
                    'scientific_name' => $t['sci'],
                    'description' => $t['desc'] . ' Commonly found in ' . $t['region'] . '.',
                    'lifespan_min' => $t['life'][0],
                    'lifespan_max' => $t['life'][1],
                    'height_min' => $t['height'][0],
                    'height_max' => $t['height'][1],
                    'growth_rate' => $t['growth'],
                    'status' => 1,
                    'family_name' => $t['family'],
                    'genus' => $t['genus'],
                    'species' => $t['species'],
                    'temperature_range' => $t['temp'],
                    'rainfall_requirement' => $t['rain'],
                    'water_requirement' => $t['water'],
                    'humidity' => $t['humidity'],
                    'altitude_range' => $t['alt'],
                    'sandy_soil' => $t['soil'][0],
                    'clay_soil' => $t['soil'][1],
                    'loamy_soil' => $t['soil'][2],
                    'soil_ph' => $t['ph'],
                    'leaf_type' => $t['leaf'],
                    'flowering_season' => $t['flower'],
                    'harvest_time' => $t['harvest'],
                    'production_per_tree' => $t['prod'],
                    'seed_treatment' => $t['seed'],
                    'nursery_method' => $t['nursery'] ?? $generic['nursery'],
                    'planting_distance' => $t['spacing'],
                    'fertilizer_schedule' => $t['fert'] ?? $generic['fert'],
                    'irrigation_schedule' => $t['irrig'] ?? $generic['irrig'],
                    'pruning_guide' => $t['prune'] ?? $generic['prune'],
                    'common_diseases' => $t['diseases'],
                    'common_insects' => $t['insects'],
                    'symptoms' => $t['symptoms'],
                    'treatment' => $t['treatment'],
                ];
                foreach (['seed_treatment', 'nursery_method', 'fertilizer_schedule', 'irrigation_schedule', 'pruning_guide', 'harvest_time'] as $column) {
                    if (mb_strlen($row[$column]) > self::LONG_TEXT_LIMIT) {
                        throw new \RuntimeException("{$t['en']}: $column is longer than " . self::LONG_TEXT_LIMIT . ' characters');
                    }
                }

                $this->connection->insert('tree', $row);
                $treeId = (int) $this->connection->lastInsertId();
                $this->addLocalName($treeId, 'English', $t['en']);
                $this->addLocalName($treeId, 'Hindi', $t['hi']);
                foreach ($t['uses'] as [$title, $description]) {
                    $this->connection->insert('uses', ['title' => $title, 'description' => $description, 'status' => 1, 'treeId' => $treeId]);
                }
                foreach ($t['categories'] as $category) {
                    $this->connection->insert('tree_categories', ['tree_id' => $treeId, 'category_id' => $this->categoryId($category)]);
                }

                $io->writeln("add   {$t['en']} ({$t['sci']}) as #$treeId");
                ++$added;
            }

            $aliases = 0;
            foreach ($data['aliases'] as $scientificName => $names) {
                $treeIds = $this->connection->fetchFirstColumn('SELECT id FROM tree WHERE scientific_name = ?', [$scientificName]);
                foreach ($treeIds as $treeId) {
                    foreach ($names as [$language, $name]) {
                        $exists = $this->connection->fetchOne('SELECT COUNT(*) FROM local_names WHERE treeId = ? AND localName = ?', [$treeId, $name]);
                        if (!$exists) {
                            $this->addLocalName((int) $treeId, $language, $name);
                            ++$aliases;
                        }
                    }
                }
            }

            if ($dryRun) {
                $this->connection->rollBack();
                $io->note("Dry run: would add $added trees and $aliases local name aliases. Nothing was written.");
            } else {
                $this->connection->commit();
                $io->success("Added $added trees and $aliases local name aliases.");
            }
        } catch (\Throwable $e) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }
            throw $e;
        }

        return Command::SUCCESS;
    }

    // Moru and Tilonj share a scientific name, so the English name is part of the identity.
    private function treeExists(string $scientificName, string $englishName): bool
    {
        return (bool) $this->connection->fetchOne(
            'SELECT COUNT(*) FROM tree t JOIN local_names n ON n.treeId = t.id WHERE t.scientific_name = ? AND n.language = ? AND n.localName = ?',
            [$scientificName, 'English', $englishName],
        );
    }

    private function addLocalName(int $treeId, string $language, string $name): void
    {
        $this->connection->insert('local_names', ['language' => $language, 'localName' => $name, 'treeId' => $treeId]);
    }

    private function categoryId(string $name): int
    {
        $id = $this->connection->fetchOne('SELECT id FROM category WHERE name = ?', [$name]);
        if (false === $id) {
            $this->connection->insert('category', ['name' => $name]);
            $id = $this->connection->lastInsertId();
        }

        return (int) $id;
    }
}
