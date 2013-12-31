Given(/^a user is on the projects participant page$/) do
 verify_user_is_on_participant_page
end

Given(/^a user is on the score Entry User page$/) do
  verify_user_is_on_score_entry_user_page
end

Given(/^a user is on the admin page$/) do
  verify_user_is_on_admin_page
end

When(/^User click on Add Participant$/) do
  get_number_of_participant_rows
  click_on_button_add_participant
end

When(/^user click on add Score entry user$/) do
  click_on_button_add_score_entry_user
end

When(/^user click on add admin$/) do
  click_on_button_add_admin
end

When(/^User click on sort Participant$/) do
  click_on_sort_participant
end

Then(/^User should see the registration type has list of "(.*?)", "(.*?)", "(.*?)" and "(.*?)"$/) do |user_type1, user_type2, user_type3, user_type4|
 verify_list_of_user_registration_type_contains(user_type1,user_type2,user_type3,user_type4)
end

Then(/^User be able to register a participant with details$/) do
  enter_user_participant_details
end

Then(/^User be able to register a score entry user with details$/) do
  enter_score_entry_user_participant_details
  verify_user_added_to_score_entry_user_table
end

Then(/^User be able to register admin with details$/) do
  enter_admin_participant_details
  verify_user_added_to_admin_table
end

Then(/^User be able to register a judge with details$/) do
  enter_user_judge_details_all
end

Then(/^the user should be added to the participants tab$/) do
  verify_user_added_to_the_list
end

Then(/^User click on the edit icon$/) do
  click_on_the_edit_user_icon
end

Then(/^User click on the edit user$/) do

end

Then(/^the user should be taken to user modification page$/) do
  verify_user_on_the_modification_page
end

Then(/^the user should be added to the judge tab$/) do
  verify_user_added_to_the_judge_list
end

Then(/^the user should be added to the manage users tab$/) do
  verify_user_added_to_the_manage_users
  end


Then(/^User be able to register a participant with out email$/) do
  enter_user_participant_details_without_email
end

Then(/^User be able to register a participant without optional parameters$/) do
  enter_user_participant_details_without_optional_parameters
end

Then(/^an "(.*)" missing error should be thrown$/) do |error_message|
  verify_error_with_required_message(error_message)
end

Given(/^a user is on the projects judge page$/) do
  verify_user_is_on_judge_page
end

When(/^User click on Add judge$/) do
  click_on_button_add_judge
end
Then(/^the new user type should be judge$/) do
  verify_user_type_when_add_judge
end

When(/^the user first name is edited$/) do
  edit_user_info_first_name

end

Then(/^in the participant tab user information should be updated$/) do
  verify_user_info_has_updated


end
When(/^user search for the participant name$/) do
  search_for_user_last_name
end
Then(/^the user should see the list of search result$/) do
  search_result_has_user_searched
end

Given(/^a user has registered a participant$/) do
  step "a user is on the projects participant page"
  step "User click on Add Participant"
  step "User be able to register a participant with details"
end