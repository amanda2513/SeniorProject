Given(/^I am at a page$/) do
  visit "http://gradproject.dynu.com/"
end

When(/^click "([^"]*)"$/) do |create_user_button|
  click_button(create_user_button)
end


Given /^a user fills the registration page with correct details$/ do
  user_fills_registration_page_with_correct_details
end

Then /^user should receive successful registration email in HTML format$/ do
  check_if_user_received_email
end

Given /^a user fills the registration page with invalid email$/ do
  user_fills_registration_page_with_incorrect_email
end

Then /^An error is returned with a "(.*?)" error$/ do |arg1|
  #check for error on the page,
end

Given /^a user fills the registration page with incorrect password$/ do
  user_fills_registration_page_with_incorrect_password
end

Given /^a user fills the registration page with already registered email$/ do
  user_tries_to_register_with_already_registered_user
end

Then /^An error is returned with a email "(.*?)" error$/ do |arg1|
    #error displayed with tried to register with already registered user
end

When(/^User press "([^"]*)"$/) do |button_to_click|
  user_clicks_a_button(button_to_click)
end

# This methos check if we are on the dispostable.com and also the user has it email box with an email
def check_if_user_received_email
  page = Nokogiri::HTML(RestClient.get("http://www.dispostable.com/inbox/#{@user_name}"))
  begin
    page = Nokogiri::HTML(RestClient.get("http://www.dispostable.com/inbox/#{@user_name}"))
    page_object = page.css('td')[2]
  end until page_object != nil
  @check_subject = page_object.children[0].children[0].children[0].text
  @check_subject.should include("Successful Registration") #verifying Subject for successful Registration

  @inbox_url = page_object.children[0].attributes['href'].value
  page_new = Nokogiri::HTML(RestClient.get("http://www.dispostable.com/#{@inbox_url}"))
  message_body = page_new.xpath('//div[@id="message"]').children[0].text
  message_body.should include("Successful Registration")  #verifying the message body with Successful registration message
end


When(/^I have logged in a user$/) do
  login_with_user_details
end
