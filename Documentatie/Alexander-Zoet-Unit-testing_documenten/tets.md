# Testen van `RestaurantLogin.vue`

Dit document beschrijft de tests voor het Vue-component `RestaurantLogin.vue`, waarin de loginfunctionaliteit voor restaurantbeheerders wordt getest. De belangrijkste functionaliteiten die getest worden zijn:
- Validatie van het inlogformulier
- Foutafhandeling bij onjuiste inloggegevens (401)
- Opslaan van loginstatus en restaurantgegevens in `localStorage`
- Redirect naar het dashboard na een succesvolle login

## Vereisten

Voordat je de tests uitvoert, zorg ervoor dat de volgende zaken correct zijn ingesteld:
- **Vitest**: Testframework voor Vue.
- **Mock van `localStorage`**: Omdat we de loginstatus lokaal opslaan in `localStorage`, moeten we dit mocken voor de tests.
- **Mock van `fetch`**: We mocken de netwerkrequest naar de backend API om specifieke scenario's (succesvol en mislukte login) te testen.
- **Mock van de router**: We gebruiken een mock voor de router om de redirect na succesvolle login te testen.

## Test Cases

### 1. **Toont foutmelding bij leeg formulier**

Deze test controleert of er een foutmelding wordt getoond wanneer het formulier wordt ingediend zonder dat de velden ingevuld zijn.

**Testlogica**:
- Wanneer de gebruiker het formulier indient zonder de velden in te vullen, moet de foutmelding "Vul alle velden in" verschijnen.

**Code**:
```javascript
it('toont foutmelding bij leeg formulier', async () => {
  const wrapper = mount(RestaurantLogin);

  await wrapper.find('form').trigger('submit');

  expect(wrapper.text()).toContain('Vul alle velden in');
});
