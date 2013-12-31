Then(/^the user should be registered successfully$/) do
  find(:xpath, ".//div[@id='wsu_alert']").value == "We sent you a confirmation email. Please reply to complete your registration."
  #page.should have_content("We sent you a confirmation email. Please reply to complete your registration")
end

When(/^User register a participant with details$/) do
  find(:id, 'participant_register_btn').click
  enter_user_participant_details_for_registration
  find(:id, 'register_btn').click
end
Then(/^user should see a option to enter user details$/) do
  find(:id, 'email')
  find(:id, 'password')

end
When(/^should have the signin button$/) do
  find_button("sign_in_btn")
end

Then(/^user should see a option to register as participant$/) do
  page.execute_script "window.scroll(0,10000)"
  sleep 2
  find(:id, "participant_register_btn")
  p find('a#participant_register_btn')['href']
  find('a#participant_register_btn')['href'].should include("http://gradproject.dynu.com/gerss/registration?type=participant")
end

When(/^user should see a option to register as judge$/) do
  find(:id, "judge_register_btn")
  p find('a#judge_register_btn')['href']
  find('a#judge_register_btn')['href'].should include("http://gradproject.dynu.com/gerss/registration?type=judge")
end

When(/^the logo should have link to wayne website$/) do
  find(:xpath, "//div[@id='wsu_header']/a")['href']
end

Then(/^the page header should have wayne logo$/) do
  p find(:css, "#wsu_logo")
  find(:css, "#wsu_logo")
end