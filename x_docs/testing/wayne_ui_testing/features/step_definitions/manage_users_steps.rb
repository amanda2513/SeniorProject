Given(/^I am on Participants page in manage users page$/) do
  visit "http://gradproject.dynu.com/manage_users/participants"
end

Given(/^a user is on the judge manage user page$/) do
  visit "http://gradproject.dynu.com/manage_users/judge"
end



When(/^I delete all the entries in participant table$/) do
  visit "http://gradproject.dynu.com/manage_users/participant"
  sleep 2
  @table_size = page.all('table#users_participants_table tbody tr').count
  p @table_size

  row_deleted = 1

  while row_deleted <= @table_size
    find(:xpath, ".//*[@id='users_participants_table']/tbody/tr[1]/td[4]/button").click
    sleep 1
    find(:xpath, "//html/body/div[4]/div[2]/a[2]").click
    row_deleted = row_deleted + 1
  end

end

Then(/^I should see no entries in the table in the participant table$/) do
  page.all('table#users_participants_table tbody tr').count.should == 1
  page.has_content?("No results found.")
end



Then(/^user should be able to delete the participant$/) do
  visit "http://gradproject.dynu.com/manage_users/participant"
  i = 1
  p @last_name
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name

    i += 1
  end
  p i
  #find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[1]/td[4]/button").click
  find(:xpath, ".//*[@id='users_participants_table']/tbody/tr[#{i}]/td[4]/button").click
  sleep 2
  find(:xpath, "//html/body/div[4]/div[2]/a[2]").click
end

Then(/^user should be able to delete the judge$/) do
  visit "http://gradproject.dynu.com/manage_users/judge"
  i = 1
  p @last_name
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name

    i += 1
  end
  p i
  #find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[1]/td[4]/button").click
  find(:xpath, ".//*[@id='users_judge_table']/tbody/tr[#{i}]/td[4]/button").click
  sleep 2
  find(:xpath, "//html/body/div[4]/div[2]/a[2]").click
end

Then(/^the user should be able to edit participant$/) do
  sleep 3
  visit "http://gradproject.dynu.com/manage_users/participant"

  i = 1
  p @last_name
  until find(:xpath, "//*[@id='users_participants_table']/tbody/tr[#{i}]/td[1]").text == @last_name

    i += 1
  end
  p i
  #find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[1]/td[4]/button").click
  find(:xpath, ".//*[@id='users_participants_table']/tbody/tr[#{i}]/td[4]/a[2]").click
  sleep 2
  fill_in("firstname", :with => "Test_Edit_from_manage_user_page")
  #find(:xpath, ".//*[@id='basic_info_span']/div[1]/div/input[1]").set("Test_Edit_from_manage_user_page")
  find_button("Update User Info").click
  page.should have_content("User Info Has Been Updated")
  visit "http://gradproject.dynu.com/manage_users/participant"
  sleep 2
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[2]").text == "Test_Edit_from_manage_user_page"
    i += 1
  end

end

Then(/^the project category should not be zero$/) do
  visit "http://gradproject.dynu.com/gerss/projects_participants"
  sleep 3
  j = 1
  until find(:xpath, "//*[@id='users_participants_table']/tbody/tr[#{j}]/td[1]").text == @first_name
    j += 1
  end
  p j
    @project_category_value = find(:xpath, ".//*[@id='project_participants_table']/tbody/tr[#{j}]/td[3]").text
  #  .//*[@id='users_participants_table']/tbody/tr[6]/td[4]/a[2]
  #.//*[@id='project_participants_table']/tbody/tr[8]/td[3]
    p @project_category_value
    @project_category_value != 0

end