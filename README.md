# ProjetoIndDoces

# Importar matérias-primas

a tinker

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MateriaPrimaImport;

Excel::import(new MateriaPrimaImport, storage_path('app/materiasPrimas.xlsx'));

# No FDB

a app:sincronizar-produtos-industria
