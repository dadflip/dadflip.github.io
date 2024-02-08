import subprocess
import sys
import platform

def install_dependencies():
    # Vérifier si Python est installé
    if not sys.version_info.major == 3:
        print("Python 3 est requis pour exécuter ce script.")
        sys.exit(1)

    # Installer les dépendances avec pip
    try:
        subprocess.run([sys.executable, '-m', 'pip', 'install', '-r', 'requirements.txt'], check=True)
        print("Dépendances installées avec succès.")
    except subprocess.CalledProcessError as e:
        print(f"Erreur lors de l'installation des dépendances : {e}")
        sys.exit(1)

if __name__ == "__main__":
    print("Vérification de l'installation de Python...")

    # Vérifier le système d'exploitation
    system = platform.system()
    if system == "Windows":
        try:
            subprocess.run(['where', 'python'], stdout=subprocess.PIPE, check=True)
        except subprocess.CalledProcessError:
            print("Python n'est pas installé. Veuillez installer Python 3 avant d'exécuter ce script.")
            sys.exit(1)
    elif system == "Linux" or system == "Darwin":  # Linux ou macOS
        try:
            subprocess.run(['which', 'python'], stdout=subprocess.PIPE, check=True)
        except subprocess.CalledProcessError:
            print("Python n'est pas installé. Veuillez installer Python 3 avant d'exécuter ce script.")
            sys.exit(1)
    else:
        print(f"Système d'exploitation non pris en charge : {system}")
        sys.exit(1)

    # Installer les dépendances
    install_dependencies()
