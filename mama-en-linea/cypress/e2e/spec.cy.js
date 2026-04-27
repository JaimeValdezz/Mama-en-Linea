describe('Pruebas de Acceso por Teléfono y Aprobación', () => {
  it('Debe ingresar con número telefónico y aprobar vacante', () => {
    // 1. Visitar el Login
    cy.visit('http://localhost:81/web3/dev/public/login')

    // 2. Ingresar número de teléfono de prueba
    cy.get('input[name="phone"]').type('6188387171')
    cy.get('input[name="password"]').type('JaimeValdez')
    cy.get('button.btn-login').click() 


    // 4. Navegar a Gestión y aprobar
    cy.visit('http://localhost:81/web3/dev/public/admin-gestion')
    cy.get('button.btn.btn-sm.btn-outline-danger.rounded-pill.px-4.shadow-sm').first().click()

    // 5. Verificar que el estado cambió
    cy.visit('http:/dc/localhost:81/web3/dev/public/vacantes')
    cy.get('a.btn-postular-lila-')
  })
})