<?php

namespace gestionFilms\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * film
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class film
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=1000)
     */
    private $titre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=1500)
     */
    private $resume;
    
    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="acteurPrincipal", type="string", length=255)
     */
    private $acteurPrincipal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="acteursescondaires", type="string", length=512)
     */
    private $acteursescondaires;
    
    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=512 , nullable=true)
     */
    private $photo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneeSortie", type="date")
     */
    private $anneeSortie;
    
    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=255)
     */
    private $langue;
    
    /**
     * @var string
     *
     * @ORM\Column(name="realisateur", type="string", length=255)
     */
    private $realisateur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;
    
    private $file;
    
    private $tempFilename;
    
    
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        if (null !== $this->photo) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->photo;
            
            // On réinitialise les valeurs des attributs url et alt
            $this->photo = null;
        }
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        
        // Et on génère l'attribut photo, à la valeur du nom du fichier sur le PC de l'internaute
        $this->photo = $this->file->getClientOriginalName();
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        
        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '_' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(), // Le répertoire de destination
            $this->id . '_' . $this->photo // Le nom du fichier à créer, ici « id.extension »
            );
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->id . '_' . $this->photo;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }
    
    public function getWebPath()
    {
        return $this->getUploadDir() . '/' . $this->getId() . '_' . $this->getPhoto();
    }
    
    
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/img/film';
    }
    
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set titre
     *
     * @param string $titre
     * @return film
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        
        return $this;
    }
    
    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }
    
    /**
     * Set resume
     *
     * @param string $resume
     * @return film
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
        
        return $this;
    }
    
    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }
    
    /**
     * Set genre
     *
     * @param string $genre
     * @return film
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        
        return $this;
    }
    
    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }
    
    /**
     * Set acteurPrincipal
     *
     * @param string $acteurPrincipal
     * @return film
     */
    public function setActeurPrincipal($acteurPrincipal)
    {
        $this->acteurPrincipal = $acteurPrincipal;
        
        return $this;
    }
    
    /**
     * Get acteurPrincipal
     *
     * @return string 
     */
    public function getActeurPrincipal()
    {
        return $this->acteurPrincipal;
    }
    
    /**
     * Set anneeSortie
     *
     * @param \DateTime $anneeSortie
     * @return film
     */
    public function setAnneeSortie($anneeSortie)
    {
        $this->anneeSortie = $anneeSortie;
        
        return $this;
    }
    
    /**
     * Get anneeSortie
     *
     * @return \DateTime 
     */
    public function getAnneeSortie()
    {
        return $this->anneeSortie;
    }
    
    /**
     * Set langue
     *
     * @param string $langue
     * @return film
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;
        
        return $this;
    }
    
    /**
     * Get langue
     *
     * @return string 
     */
    public function getLangue()
    {
        return $this->langue;
    }
    
    /**
     * Set realisateur
     *
     * @param string $realisateur
     * @return film
     */
    public function setRealisateur($realisateur)
    {
        $this->realisateur = $realisateur;
        
        return $this;
    }
    
    /**
     * Get realisateur
     *
     * @return string 
     */
    public function getRealisateur()
    {
        return $this->realisateur;
    }
    
    /**
     * Set acteursescondaires
     *
     * @param string $acteursescondaires
     * @return film
     */
    public function setActeursescondaires($acteursescondaires)
    {
        $this->acteursescondaires = $acteursescondaires;
        
        return $this;
    }
    
    /**
     * Get acteursescondaires
     *
     * @return string 
     */
    public function getActeursescondaires()
    {
        return $this->acteursescondaires;
    }
    
    /**
     * Set photo
     *
     * @param string $photo
     * @return film
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        
        return $this;
    }
    
    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set duree
     *
     * @param string $duree
     * @return film
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string 
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
