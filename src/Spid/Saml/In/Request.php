<?php
/**
 * Created by PhpStorm.
 * User: lorenzocattaneo
 * Date: 16/08/18
 * Time: 09:52
 */

namespace SpidPHP\Spid\Saml\In;


class Request extends Base
{

    public function validate()
    {
        if (!isset($_POST) || !isset($_POST['SAMLResponse']))
        {
            throw new \Exception("SAML response not found");
        }

        $xmlString = base64_decode($_POST['SAMLResponse']);
        $xml = new \DOMDocument();
        $xml->loadXML($xmlString);

        $root = $xml->getElementsByTagName('Response')->item(0);
        if ($root->getAttribute('Version') == "")
        {
            throw new \Exception("missing Version attribute");
        }
        elseif ($root->getAttribute('Version') != '2.0')
        {
            throw new \Exception("Invalid Version attribute");
        }
        if ($root->getAttribute('IssueInstant') == "")
        {
            throw new \Exception("Missing IssueInstant attribute");
        }
        if ($root->getAttribute('InResponseTo') == "" || !isset($_SESSION['RequestID']))
        {
            throw new \Exception("Missing InResponseTo attribute");
        }
        elseif ($root->getAttribute('InResponseTo') != $_SESSION['RequestID'])
        {
            throw new \Exception("Invalid InResponseTo attribute, expected " . $_SESSION['RequestID']);
        }
        if ($root->getAttribute('Destination') == "")
        {
            throw new \Exception("Missing Destination attribute");
        }

        if ($xml->getElementsByTagName('Status')->length <= 0)
        {
            throw new \Exception("Missing Status element");
        }
        elseif ($xml->getElementsByTagName('StatusCode')->item(0)->getAttribute('Value') == 'urn:oasis:names:tc:SAML:2.0:status:Success')
        {
            if ($xml->getElementsByTagName('Assertion')->length <= 0)
            {
                throw new \Exception("Missing Assertion element");
            }
            elseif ($xml->getElementsByTagName('AuthnStatement')->length <= 0)
            {
                throw new \Exception("Missing AuthnStatement element");
            }
        }
        else
        {
            // Status code != success
            return false;
        }

        // Response OK
        unset($_SESSION['RequestID']);
        return true;


        idp_id          => $self->Issuer,
        nameid          => $self->NameID,
        session_index   => $self->SessionIndex,
        attributes      => $self->attributes,
        level           => $self->spid_level,
        assertion_xml   => $self->xml,
    }

    public function spidSession()
    {


    }
}