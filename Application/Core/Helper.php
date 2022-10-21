<?php
namespace Application\Core;

class Helper
{

    /**
     * Envoi d'email
     */
    static function sendMail(string $mailDest, string $message, string $object) : bool
    {
        $headers = [
            'MIME-Version' => '1.0',  // Version MIME
            'Content-type' => 'text/html; charset=ISO-8859-1', // l'en tete Content-type pour le format HTML
            'Reply-To' => EMAIL['MAIL'], // Mail de réponse
            'From' => EMAIL['NAME'], // Expéditeur
            'Delivered-to' => $mailDest // Destinataire
        ];
        return mail($mailDest, $object, $message, $headers);
    }
    
    /**
     * Savoir si le mail à un format valide
     */
    static function validMail(string $email) : bool
    {
        return !((filter_var($email, FILTER_VALIDATE_EMAIL) === false));
    }

    /**
     * Système de pagination basique (il faudrait créer une petite vue pour ce fichier afin de séparer le HTML)
     */
    static function paginate($total_page, $current_page, $link , $template = null): string
    {

        $return = '
<nav aria-label="Page navigation ">
    <ul class="pagination justify-content-end">
        <li class="page-item'.(($current_page == 0) ? ' disabled' : '').'">
            <a class="page-link" '.(($current_page > 0) ? 'href="'.$link.($current_page).'"' : '').'>Previous</a>
        </li>';
        for($i=0; $i < $total_page; ++$i){
            $return .="\r\n".'    <li class="page-item"><a class="page-link '.(($current_page == $i) ? ' disabled' : '').'" '.(($current_page == $i) ? '' : 'href="'.$link.($i+1).'"').'>'.($i+1).'</a></li>';
        }
        $return .= "\r\n".'
        <li class="page-item'.(($current_page == $total_page-1) ? ' disabled' : '').'">
            <a class="page-link" '.(($current_page < $total_page-1) ? 'href="'.$link.($current_page+2).'"' : '').'>Next</a>
        </li>
    </ul>
</nav>';
        return $return;
    }
}