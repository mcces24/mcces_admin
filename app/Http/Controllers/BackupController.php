<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Backup;
use Response;

class BackupController extends Controller
{
    public function backup()
    {
        // Connection credentials (you can also use env() values here)
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');

        // Create mysqli connection
        $mysqli = new \mysqli($host, $username, $password, $dbname);
        if ($mysqli->connect_error) {
            return response()->json(['error' => 'Connection failed: ' . $mysqli->connect_error], 500);
        }

        // File name with timestamp
        $backup_file = storage_path('backups/MCCES_DATABASE_backup_' . date('Y-m-d_H-i-s') . '.sql');
        $filename = 'MCCES_DATABASE_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $dir = 'storage/backups/' . $filename;

        // Initialize backup file
        File::put($backup_file, ''); // Empty the file if it exists

        // Get tables in the database
        $query = $mysqli->query('SHOW TABLES');
        if (!$query) {
            return response()->json(['error' => 'Unable to fetch tables: ' . $mysqli->error], 500);
        }

        while ($table = $query->fetch_row()) {
            $table_name = $table[0];

            // Get CREATE TABLE statement
            $create_table_query = $mysqli->query("SHOW CREATE TABLE $table_name");
            if (!$create_table_query) {
                return response()->json(['error' => 'Unable to fetch create table for ' . $table_name], 500);
            }
            $create_table_row = $create_table_query->fetch_row();
            File::append($backup_file, $create_table_row[1] . ";\n\n");

            // Get table data
            $select_query = $mysqli->query("SELECT * FROM $table_name");
            while ($row = $select_query->fetch_assoc()) {
                $columns = implode(", ", array_keys($row));
                $values = implode("', '", array_map([$mysqli, 'real_escape_string'], array_values($row)));
                File::append($backup_file, "INSERT INTO $table_name ($columns) VALUES ('$values');\n");
            }
        }

        // Close the connection
        $mysqli->close();

        //check if backup success
        if (!File::exists($backup_file)) {
            return response()->json(['status' => 'failed', 'message' => 'There was an error creating the backup file']);
        } else {
            //save to database backupmdel
            $backup = new Backup();
            $backup->filename = $filename;
            $backup->filesize = File::size($backup_file);
            $backup->backup_date = date('Y-m-d H:i:s');
            $backup->backup_dir = $dir;
            $backup->save();            

            // Return response
            return response()->json(['status' => 'success', 'message' => 'Backup completed successfully', 'file' => $backup_file]);
        }
    }

    public function download($filename)
    {
        // Path to the backup directory where the files are stored
        $path = storage_path('backups/' . $filename);  // Ensure this path is correct

        // Check if the file exists
        if (file_exists($path)) {
            // Return the file as a download response
            return response()->download($path);
        } else {
            // Return a 404 error if the file doesn't exist
            return abort(404, 'File not found.');
        }
    }
}
