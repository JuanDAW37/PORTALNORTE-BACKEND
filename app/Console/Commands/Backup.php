<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Carbon\Carbon;

class Backup extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Crea una copia de seguridad de la base de datos PortalNorte';

    /**
     * Creo una instancia del comando
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta el comando de copia de seguridad.
     *
     * @return void
     */
    public function handle():void
    {
        $databaseName = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT');

        $fileName = "{$databaseName}_{$this->getBackupDate()}.sql";
        $backupPath = storage_path("app/backup/{$fileName}");

        $this->info("Creando una copia de la base de datos '{$databaseName}'...");

        $command = 'mysqldump -h '.$host. ' -u '. $username .' -p '.' '. $databaseName . '>'. $backupPath;

        exec($command,$output,$worked);

        switch($worked){
            case 0:
                echo 'La base de datos ' .$databaseName .' se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .$backupPath;
                break;
            case 1:
                echo 'Se ha producido un error al exportar ' .$databaseName .' a '.getcwd().'/' .$backupPath;
                break;
            case 2:
                echo 'Se ha producido un error de exportación, compruebe la siguiente información: Nombre de la base de datos: ' .$databaseName .' Nombre de usuario MySQL: ' .$username .' Contraseña MySQL: '. $password. ' Nombre de host MySQL: ' .$host;
                break;
        }
    }

    /**
     * Coge la fecha y hora del sistema y la devuelve
     * @return Carbon string
     */
    private function getBackupDate():string
    {
        return Carbon::now()->format('d-m-Y_H-i-s');
    }
}
