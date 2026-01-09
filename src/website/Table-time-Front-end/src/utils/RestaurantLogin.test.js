import { describe, it, expect, vi, beforeEach } from 'vitest';
import RestaurantLogin from '../pages/RestaurantLogin.vue';

describe('RestaurantLogin.vue', () => {

    let instance;

    beforeEach(() => {
        vi.restoreAllMocks();

        global.localStorage = {
            store: {},
            getItem(key) { return this.store[key] || null },
            setItem(key, value) { this.store[key] = value },
            removeItem(key) { delete this.store[key] },
            clear() { this.store = {} }
        };

        localStorage.clear();

        // Maak een 'instance' van de component
        instance = {
            ...RestaurantLogin.data(),
            ...RestaurantLogin.methods
        };

        // Mock router push
        instance.$router = { push: vi.fn() };
    });

    it('toont foutmelding bij leeg formulier', async () => {
        // Form leeg
        instance.form.email = '';
        instance.form.wachtwoord = '';

        await instance.submitLogin();

        expect(instance.message).toBe('Vul alle velden in');
        expect(instance.messageType).toBe('error');
    });

    it('toont foutmelding bij verkeerde login (401)', async () => {
        global.fetch = vi.fn().mockResolvedValue({
            ok: false,
            status: 401,
            text: async () => 'Unauthorized'
        });

        instance.form.email = 'test@test.nl';
        instance.form.wachtwoord = 'fout';

        await instance.submitLogin();

        expect(instance.message).toBe('Onjuiste inloggegevens');
        expect(instance.messageType).toBe('error');
    });

    it('slaat login op en redirect bij succesvolle login', async () => {
        global.fetch = vi.fn().mockResolvedValue({
            ok: true,
            json: async () => ({ Restaurant: { id: 3, naam: 'Testaurant' } })
        });

        const push = vi.fn(); // Mock router.push

        instance.$router.push = push; // Zet de mock push in de instantie van $router

        instance.form.email = 'goed@test.nl';
        instance.form.wachtwoord = '1234';

        await instance.submitLogin(); // Wacht op de async login

        // Verifieer dat de localStorage correct is bijgewerkt
        expect(localStorage.getItem('isLoggedIn')).toBe('true');
        expect(localStorage.getItem('restaurantData')).toBe(JSON.stringify({ id: 3, naam: 'Testaurant' }));

        // Controleer of de router.push methode is aangeroepen met het juiste argument
        expect(push).toHaveBeenCalledWith('/restaurant/3/dashboard');
    });


});
