When /^the user enter all the required information$/ do
  enter_judge_registration_details
end

Then /^judge should be added$/ do
  page.should have_content("User Added")
end

Then /^the same details should be present in the judge table$/ do
  verify_judge_added_to_table
end


Given(/^a judge is added to the judge table$/) do
  step "a user is on the projects judge page"
  step "User click on Add judge"
  step "the user enter all the required information"
  step "judge should be added"
  step "the same details should be present in the judge table"
end

When(/^I click on modify judge details$/) do
  click_on_modify_judge_icon
end

When(/^I click on modify judge details on manage user page$/) do
  click_on_modify_judge_icon_on_manage_user_page
end

Then(/^I should see same judges details$/) do
  verify_judge_details_in_edit_page
end

Then (/^I should be able to edit first name$/) do
  modify_first_name_judge_details
  verify_judge_table_for_edited_first_name
end