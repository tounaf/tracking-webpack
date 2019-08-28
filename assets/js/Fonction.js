export default class Fonction {

    /**
     * Regex phone number
     * @param str
     * @returns {boolean}
     */
    static isPhone(str) {
        // var regexPhone = /^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g;
        var regexPhone = /^(0|\+261)[\s-]*[0-9]{2}[\s-]*[0-9]{2}[\s-]*[0-9]{3}[\s-]*[0-9]{2}$/;

        return  regexPhone.test(str);
    }

    /**
     * Regex format email
     * @param str
     * @return {boolean}
     */
    static isEmailValidFormat(email) {
        var pattern = new RegExp(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        return pattern.test(email);
    }
}